<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

use Closure;
use InvalidArgumentException;
use Latte\Essential\RawPhpExtension;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\SubmitButton;
use Nette\Utils\Paginator;
use OriCMF\UI\Control\BaseControl;
use Orisai\Exceptions\Logic\InvalidArgument;
use function array_intersect;
use function array_map;
use function assert;
use function is_array;
use function is_string;
use function Orisai\TranslationContracts\t;

/**
 * @property-read DataGridTemplate $template
 */
final class DataGrid extends BaseControl
{

	public const OrderAsc = 'asc',
		OrderDesc = 'desc';

	public const TemplatePath = __DIR__ . '/DataGrid.latte';

	/** @var array<string, mixed> */
	#[Persistent]
	public array $filter = [];

	#[Persistent]
	public string|null $orderColumn = null;

	/** @var self::Order* */
	#[Persistent]
	public string $orderType = self::OrderAsc;

	/** @var int<1, max> */
	#[Persistent]
	public int $page = 1;

	/** @var array<string, mixed> */
	private array $filterDataSource = [];

	/** @var array<string, Column> */
	private array $columns = [];

	/** @var (Closure(): Container)|null */
	private Closure|null $filterFormFactory = null;

	/** @var array<string, mixed> */
	private array $filterDefaults = [];

	/** @var array<string, array{string, (Closure(array<(int|string)>, $this): void)}> */
	private array $globalActions = [];

	private Paginator|null $paginator = null;

	/** @var (Closure(SearchParameters): (int|null))|null */
	private Closure|null $paginatorItemsCountCallback = null;

	/** @var array<mixed>|null */
	private array|null $data = null;

	private bool $sendOnlyRowParentSnippet = false;

	/** @var int<1, max> */
	private int $itemsPerPage = 50;

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
			$filterContainer = $this['form']['filter'];
			assert($filterContainer instanceof Container);
			$filterContainer->setDefaults($this->filter);
		}

		$this->template->form = $this['form'];
		$this->template->data = $this->getAllData();
		$this->template->columns = $this->columns;
		$this->template->rowPrimaryKey = $this->rowPrimaryKey;
		$this->template->paginator = $this->paginator;
		$this->template->sendOnlyRowParentSnippet = $this->sendOnlyRowParentSnippet;
		// phpcs:ignore SlevomatCodingStandard.Operators.DisallowEqualOperators.DisallowedNotEqualOperator
		$this->template->showFilterCancel = $this->filterDataSource != $this->filterDefaults; // @phpstan-ignore-line

		$this->template->getLatte()->addExtension(new RawPhpExtension());
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

	/**
	 * @return array<array<mixed>>
	 */
	private function getAllData(): array
	{
		if ($this->data !== null) {
			return $this->data;
		}

		if ($this->orderColumn !== null && !isset($this->columns[$this->orderColumn])) {
			$this->orderColumn = null;
		}

		$parameters = $this->createParameters();

		if ($this->paginator !== null) {
			assert($this->paginatorItemsCountCallback !== null);
			$this->configurePage($this->paginator, $this->paginatorItemsCountCallback, $parameters);
		}

		return $this->data = ($this->dataSource)($parameters);
	}

	private function createParameters(): SearchParameters
	{
		$find = [];
		foreach ($this->filterDataSource as $column => $value) {
			$find[$column] = new FindParameter($column, $value);
		}

		$order = [];
		if ($this->orderColumn !== null) {
			$order[$this->orderColumn] = new OrderParameter($this->orderColumn, $this->orderType);
		}

		return new SearchParameters($find, $order, $this->paginator);
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
			throw new InvalidArgumentException("Result row does not have '$column' column.");
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

		if ($this->filterFormFactory !== null) {
			$form['filter'] = $filterContainer = ($this->filterFormFactory)();
			if (!isset($filterContainer['filter'])) {
				$filterContainer->addSubmit('filter', t('ori.cmf.grid.search'));
			}

			if (!isset($filterContainer['cancel'])) {
				$filterContainer->addSubmit('cancel', t('ori.cmf.grid.search.cancel'));
			}

			$this->prepareFilterDefaults($filterContainer);
			if ($this->filterDataSource === []) {
				$this->filterDataSource = $this->filterDefaults;
			}
		}

		if ($this->globalActions !== []) {
			$actions = array_map(static fn (array $row): string => $row[0], $this->globalActions);
			$form['actions'] = $actionsContainer = new Container();
			$actionsContainer->addSelect('action', t('ori.cmf.grid.global.title'), $actions)
				->setPrompt(t('ori.cmf.grid.global.choose'));
			$actionsContainer->addCheckboxList('items', '', []);
			$actionsContainer->addSubmit('process', t('ori.cmf.grid.global.do'));
		}

		$form->onSubmit[] = $this->processForm(...);

		return $form;
	}

	private function processForm(Form $form): void
	{
		$rowPrimaryKey = $this->rowPrimaryKey;

		$filterContainer = $form['filter'] ?? null;
		if ($filterContainer !== null) {
			assert($filterContainer instanceof Container);
			$filterButton = $filterContainer['filter'];
			assert($filterButton instanceof SubmitButton);
			$cancelButton = $filterContainer['cancel'];
			assert($cancelButton instanceof SubmitButton);
			if ($filterButton->isSubmittedBy()) {
				$values = $filterContainer->getValues('array');
				assert(is_array($values));
				$values = $this->filterFormFilter($values);
				if ($this->paginator !== null) {
					$this->page = 1;
					$this->paginator->setPage(1);
				}

				$this->filter = $this->filterDataSource = $values;
				$this->redrawControl('rows');
			} elseif ($cancelButton->isSubmittedBy()) {
				if ($this->paginator !== null) {
					$this->page = 1;
					$this->paginator->setPage(1);
				}

				$this->filter = $this->filterDataSource = $this->filterDefaults;
				$filterContainer->setValues($this->filter, true);
				$this->redrawControl('rows');
			}
		}

		$actionsContainer = $form['actions'] ?? null;
		if ($actionsContainer !== null) {
			assert($actionsContainer instanceof Container);
			$processButton = $actionsContainer['process'];
			assert($processButton instanceof SubmitButton);
			if ($processButton->isSubmittedBy()) {
				$actionInput = $actionsContainer['action'];
				assert($actionInput instanceof SelectBox);
				$action = $actionInput->getValue();
				if ($action !== null) {
					$rows = [];
					foreach ($this->getAllData() as $row) {
						$rows[] = $this->getCellValue($row, $rowPrimaryKey);
					}

					$ids = array_intersect($rows, $form->getHttpData($form::DATA_TEXT, 'actions[items][]'));
					[, $callback] = $this->globalActions[$action];
					$callback($ids, $this);
					$this->data = null;
					$actionsContainer->setValues(['action' => null, 'items' => []]);
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
		$this->filterDataSource = $this->filter;
		$this->paginator?->setPage($this->page);
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
			if (!$this->isEmptyValue($value)) {
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
			if (!$this->isEmptyValue($value)) {
				$filtered[$key] = $value;
			}
		}

		return $filtered;
	}

	private function isEmptyValue(mixed $value): bool
	{
		return $value === null || $value === '' || $value === [] || $value === false;
	}

}
