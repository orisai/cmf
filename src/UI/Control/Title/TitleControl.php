<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Title;

use OriCMF\UI\Control\Base\BaseControl;
use function sprintf;

/**
 * @property-read TitleTemplate $template
 */
class TitleControl extends BaseControl
{

	private string|null $site = null;

	private string|null $module = null;

	private string|null $main = null;

	private string|null $separator = '-';

	private bool $revert = false;

	/**
	 * @return $this
	 */
	public function setSite(string $site): self
	{
		$this->site = $site;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setModule(string $module): self
	{
		$this->module = $module;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setMain(string $main): self
	{
		$this->main = $main;

		return $this;
	}

	public function getMain(): string|null
	{
		return $this->main;
	}

	/**
	 * @return $this
	 */
	public function setSeparator(string $separator): self
	{
		$this->separator = $separator;

		return $this;
	}

	/**
	 * Display 'Site Module Main' instead of 'Main Module Site'
	 *
	 * @return $this
	 */
	public function revert(bool $revert = true): self
	{
		$this->revert = $revert;

		return $this;
	}

	public function getTitle(): string|null
	{
		if ($this->site === null && $this->module === null && $this->main === null) {
			return null;
		}

		if ($this->site !== null && $this->module !== null) {
			$site = sprintf('%s %s', $this->site, $this->module);
		} elseif ($this->site !== null) {
			$site = $this->site;
		} elseif ($this->module !== null) {
			$site = $this->module;
		} else {
			$site = null;
		}

		$main = $this->main;

		if ($main === null || $site === null) {
			$separator = null;
		} elseif ($this->separator === null) {
			$separator = ' ';
		} else {
			$separator = sprintf(' %s ', $this->separator);
		}

		if ($this->revert === true) {
			return $site . $separator . $main;
		}

		return $main . $separator . $site;
	}

	public function render(): void
	{
		$this->template->title = $this->getTitle();

		$this->template->render();
	}

}
