<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Body;

use Nette\Utils\Html;
use OriCMF\UI\Control\BaseControl;

/**
 * @property-read BodyTemplate $template
 */
final class BodyControl extends BaseControl
{

	private Html $element;

	public function __construct()
	{
		$this->element = Html::el('body');
	}

	/**
	 * @return $this
	 */
	public function addAttribute(string $name, string $value): self
	{
		$this->element->appendAttribute($name, $value);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setAttribute(string $name, string $value): self
	{
		$this->element->setAttribute($name, $value);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function removeAttribute(string $name): self
	{
		$this->element->removeAttribute($name);

		return $this;
	}

	public function renderStart(): void
	{
		$this->template->bodyStart = Html::fromHtml($this->element->startTag());

		$this->template->setView('start');
		$this->template->render();
	}

	public function renderEnd(): void
	{
		$this->template->bodyEnd = Html::fromHtml($this->element->endTag());

		$this->template->setView('end');
		$this->template->render();
	}

}
