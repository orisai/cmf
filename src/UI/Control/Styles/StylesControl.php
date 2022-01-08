<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Styles;

use OriCMF\UI\Control\BaseControl;

/**
 * @property-read StylesTemplate $template
 */
class StylesControl extends BaseControl
{

	/** @var array<string> */
	private array $styles = [];

	/**
	 * @return $this
	 */
	public function addStyle(string $href): self
	{
		$this->styles[] = $href;

		return $this;
	}

	public function render(): void
	{
		$this->template->styles = $this->styles;

		$this->template->render();
	}

}
