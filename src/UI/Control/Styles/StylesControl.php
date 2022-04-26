<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Styles;

use OriCMF\UI\Control\BaseControl;
use function md5;

/**
 * @property-read StylesTemplate $template
 */
class StylesControl extends BaseControl
{

	/** @var array<string> */
	private array $styles = [];

	public function __construct(private string $build)
	{
	}

	/**
	 * @return $this
	 */
	public function addStyle(string $href): self
	{
		$this->styles[] = $href . '?v=' . md5($this->build);

		return $this;
	}

	public function render(): void
	{
		$this->template->styles = $this->styles;

		$this->template->render();
	}

}
