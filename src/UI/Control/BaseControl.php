<?php declare(strict_types = 1);

namespace OriCMF\UI\Control;

use Nette\Application\UI\Control;
use Nette\Application\UI\Template as PlainTemplate;
use OriCMF\Auth\UI\BaseUIFirewall;
use OriCMF\UI\Form\FormFactory;
use OriCMF\UI\Presenter\BasePresenter;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use function assert;
use function class_exists;
use function is_subclass_of;

/**
 * @method BasePresenter getPresenter()
 * @method BaseControlTemplate getTemplate()
 * @property-read BasePresenter       $presenter
 * @property-read BaseControlTemplate $template
 */
abstract class BaseControl extends Control
{

	protected FormFactory $formFactory;

	private BaseUIFirewall|null $firewall = null;

	public function setBase(FormFactory $formFactory): void
	{
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
		return $this->checkTemplateClass(static::class . 'Template');
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
