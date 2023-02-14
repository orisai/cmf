<?php declare(strict_types = 1);

namespace OriCMF\UI\Presenter;

use Latte\Essential\RawPhpExtension;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use OriCMF\App\Config\ApplicationConfig;
use OriCMF\Auth\UI\BaseUIFirewall;
use OriCMF\Config\ConfigProvider;
use OriCMF\UI\ActionLink;
use OriCMF\UI\Control\Document\DocumentControl;
use OriCMF\UI\Control\Document\DocumentControlFactory;
use OriCMF\UI\HandleLink;
use OriCMF\UI\TemplateLocator\Template\FlexibleTemplatePresenter;
use OriNette\Application\CanonicalLink\CanonicalLinker;
use OriNette\Application\Presenter\ShortDefaultActionName;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\Localization\Translator;
use function assert;
use function class_exists;
use function is_subclass_of;

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

	use ShortDefaultActionName;
	use FlexibleTemplatePresenter;

	public const LayoutPath = __DIR__ . '/@layout.latte';

	private DocumentControlFactory $documentFactory;

	private Translator $translator;

	protected ConfigProvider $config;

	private CanonicalLinker $canonicalLinker;

	final public function injectBase(
		DocumentControlFactory $documentFactory,
		Translator $translator,
		ConfigProvider $config,
		CanonicalLinker $canonicalLinker,
	): void
	{
		$this->documentFactory = $documentFactory;
		$this->translator = $translator;
		$this->config = $config;
		$this->canonicalLinker = $canonicalLinker;
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
		$this->template->getLatte()->addExtension(new RawPhpExtension());
	}

	protected function configureCanonicalUrl(DocumentControl $document): void
	{
		$document->setCanonicalUrl(
			$this->link('//this', $this->canonicalLinker->getNonCanonicalParams($this)),
		);
	}

	protected function createComponentDocument(): DocumentControl
	{
		return $this->documentFactory->create();
	}

	protected function redirectToAction(ActionLink|HandleLink $link): never
	{
		if ($link instanceof HandleLink) {
			$this->redirectUrl($link->get());
		}

		$this->redirect($link->getDestination(), $link->getArguments());
	}

	protected function linkToAction(ActionLink|HandleLink $link): string
	{
		if ($link instanceof HandleLink) {
			return $link->get();
		}

		return $this->link($link->getDestination(), $link->getArguments());
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
		return $this->checkTemplateClass(static::class . 'Template');
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
