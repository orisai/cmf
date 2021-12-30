<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

use Closure;
use InvalidArgumentException;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\ComponentModel\IContainer;
use Nette\Forms\Container;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\SubmitButton;
use Nette\Utils\Paginator;
use OriCMF\UI\Control\Base\BaseControl;
use Orisai\Exceptions\Logic\InvalidArgument;
use function array_intersect;
use function array_map;
use function assert;
use function is_array;
use function is_string;

/**
 * @property-read DataGridTemplate $template
 */
final class DataGrid extends BaseControl
{

	public const ORDER_ASC = 'asc';

	public const ORDER_DESC = 'desc';

	public const TEMPLATE_PATH = __DIR__ . '/DataGrid.latte';

	/** @var array<mixed> */
	#[Persistent]
	public array $filter = [];

	#[Persistent]
	public string|null $orderColumn = null;

	/** @var self::ORDER_* */
	#[Persistent]
	public string $orderType = self::ORDER_ASC;

	/** @var int<1, max> */
	#[Persistent]
	public int $page = 1;

	/** @var array<string, mixed> */
	protected array $filterDataSource = [];

	/** @var array<string, Column> */
	protected array $columns = [];

	/** @var (Closure(): Container)|null */
	protected Closure|null $filterFormFactory = null;

	/** @var array<mixed> */
	protected array $filterDefaults = [];

	/** @var array<string, array{string, (Closure(array<(int|string)>, $this): void)}> */
	protected array $globalActions = [];

	protected Paginator|null $paginator = null;

	/** @var (Closure(SearchParameters): (int|null))|null */
	protected Closure|null $paginatorItemsCountCallback = null;

	/** @var array<mixed>|null */
	protected array|null $data = null;

	protected bool $sendOnlyRowParentSnippet = false;

	/** @var int<1, max> */
	protected int $itemsPerPage = 50;

	/**
	 * @param Closure(SearchParameters): array<mixed> $dataSource
	 */
	public function __construct(protected string $rowPrimaryKey, protected Closure $dataSource)
	{
	}

	public function addColumn(string $name, string $label): Column
	{
		return $this->columns[$name] = new Column($name, $label, $this);
	}

	public function getColumn(string $name): Column
	{
		if (!isset($this->columns[$name])) {
			throw InvalidArgument::create()
				->withMessage("Unknown column $name.");
		}

		return $this->columns[$name];
	}

	/**
	 * @phpstan-param (Closure(): (Container))|null $filterFormFactory
	 */
	public function setFilterFormFactory(Closure|null $filterFormFactory): void
	{
		$this->filterFormFactory = $filterFormFactory;
	}

	/**
	 * @param Closure(array<(int|string)>, $this): void $action
	 */
	public function addGlobalAction(string $name, string $label, Closure $action): void
	{
		$this->globalActions[$name] = [$label, $action];
	}

	/**
	 * @phpstan-param Closure(SearchParameters): (int|null) $itemsCountCallback
	 */
	public function setPagination(Closure $itemsCountCallback): void
	{
		$this->paginator = new Paginator();
		$this->paginator->setItemsPerPage($this->itemsPerPage);
		$this->paginatorItemsCountCallback = $itemsCountCallback;
	}

	public function removePagination(): void
	{
		$this->paginator = null;
		$this->paginatorItemsCountCallback = null;
	}

	/**
	 * @param int<1, max> $count
	 */
	public function setItemsPerPage(int $count): void
	{
		$this->itemsPerPage = $count;
		$this->paginator?->setItemsPerPage($this->itemsPerPage);
	}

	public function render(): void
	{
		if ($this->filterFormFactory !== null) {
			assert($this['form']['filter'] instanceof Container);
			$this['form']['filter']->setDefaults($this->filter);
		}

		$this->template->form = $this['form'];
		$this->template->data = $this->getAllData();
		$this->template->columns = $this->columns;
		$this->template->rowPrimaryKey = $this->rowPrimaryKey;
		$this->template->paginator = $this->paginator;
		$this->template->sendOnlyRowParentSnippet = $this->sendOnlyRowParentSnippet;
		// phpcs:ignore SlevomatCodingStandard.Operators.DisallowEqualOperators.DisallowedNotEqualOperator
		$this->template->showFilterCancel = $this->filterDataSource != $this->filterDefaults; // != intentionally

		$this->template->render();
	}

	public function redrawRow(string $primaryValue): void
	{
		if (!$this->getPresenter()->isAjax()) {
			return;
		}

		if (isset($this->filterDataSource[$this->rowPrimaryKey])) {
			$this->filterDataSource = [$this->rowPrimaryKey => $this->filterDataSource[$this->rowPrimaryKey]];
			if (is_string($this->filterDataSource[$this->rowPrimaryKey])) {
				$this->filterDataSource[$this->rowPrimaryKey] = [$this->filterDataSource[$this->rowPrimaryKey]];
			}
		} else {
			$this->filterDataSource = [];
		}

		$this->filterDataSource[$this->rowPrimaryKey][] = $primaryValue;
		parent::redrawControl('rows');
		$this->redrawControl('rows-' . $primaryValue);
	}

	public function redrawControl(string|null $snippet = null, bool $redraw = true): void
	{
		parent::redrawControl($snippet, $redraw);
		if ($snippet === null || $snippet === 'rows') {
			$this->sendOnlyRowParentSnippet = $redraw;
		}
	}

	protected function validateParent(IContainer $parent): void
	{
		parent::validateParent($parent);
		$this->monitor(Presenter::class, function (): void {
			$this->filterDataSource = $this->filter;
		});
	}

	/**
	 * @return array<array<mixed>>
	 */
	protected function getAllData(): array
	{
		if ($this->data !== null) {
			return $this->data;
		}

		if ($this->orderColumn !== null && !isset($this->columns[$this->orderColumn])) {
			$this->orderColumn = null;
		}

		$parameters = $this->createParameters(false);

		if ($this->paginator !== null) {
			assert($this->paginatorItemsCountCallback !== null);
			$this->configurePage($this->paginator, $this->paginatorItemsCountCallback, $parameters);
		}

		return $this->data = ($this->dataSource)($parameters);
	}

	/**
	 * @return array<mixed>
	 */
	protected function getRowDataByPrimaryCellValue(string $value): array
	{
		if ($this->data === null) {
			$fetchOnlyRow = $this->presenter->isAjax();
			$parameters = $this->createParameters($fetchOnlyRow);

			if ($this->orderColumn !== null && !isset($this->columns[$this->orderColumn])) {
				$this->orderColumn = null;
			}

			if (!$fetchOnlyRow && $this->paginator !== null) {
				assert($this->paginatorItemsCountCallback !== null);
				$this->configurePage($this->paginator, $this->paginatorItemsCountCallback, $parameters);
			}

			$this->data = ($this->dataSource)($parameters);
		}

		foreach ($this->data as $row) {
			if ($this->getCellValue($row, $this->rowPrimaryKey) === $value) {
				return $row;
			}
		}

		throw InvalidArgument::create()
			->withMessage("Row with primary key value {$value} not found.");
	}

	protected function createParameters(bool $fetchOnlyRow): SearchParameters
	{
		$find = [];
		foreach ($this->filterDataSource as $column => $value) {
			$find[$column] = new FindParameter($column, $value);
		}

		$order = [];
		if ($this->orderColumn !== null) {
			$order[$this->orderColumn] = new OrderParameter($this->orderColumn, $this->orderType);
		}

		$paginator = $fetchOnlyRow ? null : $this->paginator;

		return new SearchParameters($find, $order, $paginator);
	}

	/**
	 * @param Closure(SearchParameters): (int|null) $paginatorItemsCountCallback
	 */
	private function configurePage(
		Paginator $paginator,
		Closure $paginatorItemsCountCallback,
		SearchParameters $parameters,
	): void
	{
		$itemsCount = $paginatorItemsCountCallback(
			$parameters,
		);

		$paginator->setItemCount($itemsCount);
		if ($paginator->getPage() !== $this->page) {
			$this->page = 1;
			$paginator->setPage(1);
		}
	}

	/**
	 * @param array<mixed> $row
	 */
	public function getCellValue(array $row, string $column, bool $need = true): mixed
	{
		if (isset($row[$column])) {
			return $row[$column];
		}

		if ($need) {
			throw new InvalidArgumentException("Result row does not have '{$column}' column.");
		}

		return null;
	}

	public function handleSort(): void
	{
		if ($this->presenter->isAjax()) {
			$this->redrawControl('rows');
		}
	}

	protected function createComponentForm(): Form
	{
		$form = $this->formFactory->create();
		$t = $this->translator->toFunction();

		if ($this->filterFormFactory !== null) {
			$filterFormFactoryCb = $this->filterFormFactory;
			$form['filter'] = $filterContainer = $filterFormFactoryCb();
			if (!isset($filterContainer['filter'])) {
				$filterContainer->addSubmit('filter', $t('ori.grid.search'));
			}

			if (!isset($filterContainer['cancel'])) {
				$filterContainer->addSubmit('cancel', $t('ori.grid.search.cancel'));
			}

			$this->prepareFilterDefaults($filterContainer);
			if ($this->filterDataSource === []) {
				$this->filterDataSource = $this->filterDefaults;
			}
		}

		if ($this->globalActions !== []) {
			$actions = array_map(static fn (array $row): string => $row[0], $this->globalActions);
			$form['actions'] = $actionsContainer = new Container();
			$actionsContainer->addSelect('action', $t('ori.grid.global.title'), $actions)
				->setPrompt($t('ori.grid.global.choose'));
			$actionsContainer->addCheckboxList('items', '', []);
			$actionsContainer->addSubmit('process', $t('ori.grid.global.do'));
		}

		$form->onSubmit[] = fn (Form $form) => $this->processForm($form);

		return $form;
	}

	protected function processForm(Form $form): void
	{
		$rowPrimaryKey = $this->rowPrimaryKey;

		if (isset($form['filter'])) {
			assert($form['filter'] instanceof Container);
			assert($form['filter']['filter'] instanceof SubmitButton);
			assert($form['filter']['cancel'] instanceof SubmitButton);
			if ($form['filter']['filter']->isSubmittedBy()) {
				$values = $form['filter']->getValues('array');
				assert(is_array($values));
				$values = $this->filterFormFilter($values);
				if ($this->paginator !== null) {
					$this->page = 1;
					$this->paginator->setPage(1);
				}

				$this->filter = $this->filterDataSource = $values;
				$this->redrawControl('rows');
			} elseif ($form['filter']['cancel']->isSubmittedBy()) {
				if ($this->paginator !== null) {
					$this->page = 1;
					$this->paginator->setPage(1);
				}

				$this->filter = $this->filterDataSource = $this->filterDefaults;
				$form['filter']->setValues($this->filter, true);
				$this->redrawControl('rows');
			}
		}

		if (isset($form['actions'])) {
			assert($form['actions'] instanceof Container);
			assert($form['actions']['process'] instanceof SubmitButton);
			if ($form['actions']['process']->isSubmittedBy()) {
				assert($form['actions']['action'] instanceof SelectBox);
				$action = $form['actions']['action']->getValue();
				if ($action !== null) {
					$rows = [];
					foreach ($this->getAllData() as $row) {
						$rows[] = $this->getCellValue($row, $rowPrimaryKey);
					}

					$ids = array_intersect($rows, $form->getHttpData($form::DATA_TEXT, 'actions[items][]'));
					[, $callback] = $this->globalActions[$action];
					$callback($ids, $this);
					$this->data = null;
					$form['actions']->setValues(['action' => null, 'items' => []]);
				}
			}
		}

		if (!$this->getPresenter()->isAjax()) {
			$this->redirect('this');
		}
	}

	/**
	 * @param array<mixed> $params
	 */
	public function loadState(array $params): void
	{
		parent::loadState($params);
		if ($this->paginator !== null) {
			$this->paginator->setPage($this->page);
		}
	}

	public function handlePagination(): void
	{
		if ($this->getPresenter()->isAjax()) {
			$this->redrawControl('rows');
		}
	}

	private function prepareFilterDefaults(Container $container): void
	{
		$this->filterDefaults = [];
		foreach ($container->getControls() as $name => $control) {
			if ($control instanceof Button) {
				continue;
			}

			$value = $control->getValue();
			if (!self::isEmptyValue($value)) {
				$this->filterDefaults[$name] = $value;
			}
		}
	}

	/**
	 * @param array<mixed> $values
	 * @return array<mixed>
	 */
	private function filterFormFilter(array $values): array
	{
		$filtered = [];
		foreach ($values as $key => $value) {
			//TODO  - tady se filtrovalo i podle výchozí hodnoty
			//			- žádoucí pro nynější způsob ukládání filtrů (filtr v url je minimální diff vůči výchozímu stavu filtrů)
			//			- sestavení parametrů filtru musí fungovat stejně nezávisle na výchozích parametrech, použije se tahle implementace
			//		- alternativně se mohou vypisovat všechny hodnoty do url a nedávat do ní pouze prázdné
			//			- tím by bylo chování url neměnné a fixnul by se problém se selectem s výchozí hodnotou, který chci nastavit prázdný
			if (!self::isEmptyValue($value)) {
				$filtered[$key] = $value;
			}
		}

		return $filtered;
	}

	private static function isEmptyValue(mixed $value): bool
	{
		return $value === null || $value === '' || $value === [] || $value === false;
	}

}
