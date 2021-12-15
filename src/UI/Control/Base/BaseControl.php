<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Base;

use Nette\Application\UI\Control;
use Nette\Application\UI\Template as PlainTemplate;
use OriCMF\UI\Auth\BaseUIFirewall;
use OriCMF\UI\Form\FormFactory;
use OriCMF\UI\Presenter\Base\BasePresenter;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\Localization\Translator;
use function assert;
use function class_exists;
use function is_string;
use function is_subclass_of;
use function preg_replace;

/**
 * @method BasePresenter getPresenter()
 * @method BaseControlTemplate getTemplate()
 * @property-read BasePresenter       $presenter
 * @property-read BaseControlTemplate $template
 */
abstract class BaseControl extends Control
{

	protected Translator $translator;

	protected FormFactory $formFactory;

	private BaseUIFirewall|null $firewall = null;

	public function setBase(Translator $translator, FormFactory $formFactory): void
	{
		$this->translator = $translator;
		$this->formFactory = $formFactory;
	}

	protected function getFirewall(): BaseUIFirewall
	{
		return $this->firewall ??= $this->getPresenter()->getFirewall();
	}

	protected function createTemplate(): BaseControlTemplate
	{
		$templateFactory = $this->getPresenter()->getTemplateFactory();
		$template = $templateFactory->createTemplate($this, $this->formatTemplateClass());
		assert($template instanceof BaseControlTemplate);

		$template->firewall = $this->getFirewall();

		return $template;
	}

	/**
	 * @return class-string<BaseControlTemplate>
	 */
	public function formatTemplateClass(): string
	{
		$class = preg_replace('#Control$#', 'Template', static::class);
		assert(is_string($class));

		if ($class === static::class) {
			$class .= 'Template';
		}

		return $this->checkTemplateClass($class);
	}

	/**
	 * @return class-string<BaseControlTemplate>
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

		$templateClass = BaseControlTemplate::class;
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

	/**
	 * @deprecated Define filters in Template class instead
	 * @internal
	 */
	final public function templatePrepareFilters(PlainTemplate $template): void
	{
		parent::templatePrepareFilters($template);
	}

}
