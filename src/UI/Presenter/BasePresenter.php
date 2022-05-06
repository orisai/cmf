<?php declare(strict_types = 1);

namespace OriCMF\UI\Presenter;

use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\FileNotFoundException;
use OriCMF\Core\Config\ApplicationConfig;
use OriCMF\Core\Config\ConfigProvider;
use OriCMF\UI\ActionLink;
use OriCMF\UI\Auth\BaseUIFirewall;
use OriCMF\UI\Control\Document\DocumentControl;
use OriCMF\UI\Control\Document\DocumentControlFactory;
use OriCMF\UI\Template\Exception\NoTemplateFound;
use OriCMF\UI\Template\Locator\PresenterTemplateLocator;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Logic\NotImplemented;
use Orisai\Exceptions\Message;
use Orisai\Localization\Translator;
use function assert;
use function class_exists;
use function implode;
use function is_string;
use function is_subclass_of;
use function preg_match;
use function preg_replace;
use function sprintf;

/**
 * @method self getPresenter()
 * @method TemplateFactory getTemplateFactory()
 * @method BasePresenterTemplate getTemplate()
 * @property-read BasePresenter         $presenter
 * @property-read BasePresenterTemplate $template
 * @property-read null                  $user
 */
abstract class BasePresenter extends Presenter
{

	public const LayoutPath = __DIR__ . '/@layout.latte';

	private DocumentControlFactory $documentFactory;

	protected Translator $translator;

	protected ConfigProvider $config;

	private PresenterTemplateLocator $templateLocator;

	public function injectBase(
		DocumentControlFactory $documentFactory,
		Translator $translator,
		ConfigProvider $config,
		PresenterTemplateLocator $templateLocator,
	): void
	{
		$this->documentFactory = $documentFactory;
		$this->translator = $translator;
		$this->config = $config;
		$this->templateLocator = $templateLocator;
	}

	final protected function startup(): void
	{
		parent::startup();

		if ($this->isLoginRequired()) {
			$this->checkUserIsLoggedIn();
		}
	}

	abstract protected function checkUserIsLoggedIn(): void;

	abstract protected function isLoginRequired(): bool;

	abstract public function getFirewall(): BaseUIFirewall;

	protected function beforeRender(): void
	{
		parent::beforeRender();

		$document = $this['document'];
		$document->addAttribute('class', 'no-js');
		$document->setAttribute('lang', $this->translator->getCurrentLocale()->getTag());
		$document['head']['meta']->addOpenGraph('type', 'website');

		$siteName = $this->config->get(ApplicationConfig::class)->getName();
		if ($siteName !== null) {
			$document->setSiteName($siteName);
		}

		$this->configureCanonicalUrl($document);

		$this->template->firewall = $this->getFirewall();
	}

	protected function configureCanonicalUrl(DocumentControl $document): void
	{
		$document->setCanonicalUrl(
			$this->link('//this', ['backlink' => null]),
		);
	}

	protected function createComponentDocument(): DocumentControl
	{
		return $this->documentFactory->create();
	}

	/**
	 * @return never
	 */
	protected function redirectToAction(ActionLink $link): void
	{
		$this->redirect($link->getDestination(), $link->getArguments());
	}

	protected function linkToAction(ActionLink $link): string
	{
		return $this->link($link->getDestination(), $link->getArguments());
	}

	public static function formatActionMethod(string $action): string
	{
		return parent::formatActionMethod($action !== self::DEFAULT_ACTION ? $action : '');
	}

	public static function formatRenderMethod(string $view): string
	{
		return parent::formatRenderMethod($view !== self::DEFAULT_ACTION ? $view : '');
	}

	/**
	 * @return array<string>
	 * @throws NotImplemented
	 *
	 * @internal
	 */
	public function formatLayoutTemplateFiles(): array
	{
		throw NotImplemented::create()
			->withMessage(sprintf(
				'Implementation of \'%s\' is in findLayoutTemplateFiles(), do not call method directly',
				__METHOD__,
			));
	}

	/**
	 * @internal
	 */
	final public function findLayoutTemplateFile(): string|null
	{
		$layout = $this->getLayout();

		if ($layout === false) {
			return null;
		}

		if (is_string($layout) && preg_match('#/|\\\\#', $layout) === 1) {
			return $layout;
		}

		if ($layout === true || $layout === null) {
			$layout = 'layout';
		}

		try {
			return $this->templateLocator->getLayoutTemplatePath($this, $layout);
		} catch (NoTemplateFound $exception) {
			$inlinePaths = implode("\n", $exception->getShortTriedPaths());

			throw new FileNotFoundException(
				"Layout not found. None of the following templates exists:\n$inlinePaths",
				0,
				$exception,
			);
		}
	}

	/**
	 * @return array<string>
	 * @throws NotImplemented
	 *
	 * @internal
	 */
	final public function formatTemplateFiles(): array
	{
		throw NotImplemented::create()
			->withMessage(sprintf(
				'Implementation of \'%s\' is in sendTemplates(), do not call method directly',
				__METHOD__,
			));
	}

	/**
	 * @throws BadRequestException
	 * @throws AbortException
	 */
	final public function sendTemplate(Template|null $template = null): void
	{
		$template ??= $this->getTemplate();

		if ($template->getFile() === null) {
			try {
				$file = $this->templateLocator->getActionTemplatePath($this, $this->getView());
			} catch (NoTemplateFound $exception) {
				$inlinePaths = implode("\n", $exception->getShortTriedPaths());

				throw new BadRequestException(
					"Page not found. None of the following templates exists:\n$inlinePaths",
					0,
					$exception,
				);
			}

			$template->setFile($file);
		}

		$this->sendResponse(new TextResponse($template));
	}

	protected function createTemplate(): BasePresenterTemplate
	{
		$templateFactory = $this->getTemplateFactory();
		$template = $templateFactory->createTemplate($this, $this->formatTemplateClass());
		assert($template instanceof BasePresenterTemplate);

		return $template;
	}

	/**
	 * @return class-string<BasePresenterTemplate>
	 */
	public function formatTemplateClass(): string
	{
		$class = preg_replace('#Presenter$#', 'Template', static::class);
		assert(is_string($class));

		if ($class === static::class) {
			$class .= 'Template';
		}

		return $this->checkTemplateClass($class);
	}

	/**
	 * @return class-string<BasePresenterTemplate>
	 */
	protected function checkTemplateClass(string $class): string
	{
		if (!class_exists($class)) {
			$self = static::class;
			$message = Message::create()
				->withContext("Trying to create template for {$self}.")
				->withProblem("Class {$class} is required.")
				->withSolution('Create the required class and ensure it is autoloadable.');

			throw InvalidState::create()
				->withMessage($message);
		}

		$templateClass = BasePresenterTemplate::class;
		if (!is_subclass_of($class, $templateClass)) {
			$self = static::class;
			$message = Message::create()
				->withContext("Trying to create template for {$self}.")
				->withProblem("Class {$class} is not subclass of {$templateClass}.")
				->withSolution('Extend required class or its descendant.');

			throw InvalidState::create()
				->withMessage($message);
		}

		return $class;
	}

}
