<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\NoScript;

use Nette\Utils\Html;
use OriCMF\UI\Control\BaseControl;

/**
 * @property-read NoScriptTemplate $template
 */
class NoScriptControl extends BaseControl
{

	/** @var array<Html> */
	private array $noScripts = [];

	/**
	 * Add noscript <noscript>{$content|noescape}</noscript>
	 *
	 * @return $this
	 */
	public function addNoScript(string $content): self
	{
		$this->noScripts[] = Html::fromHtml($content);

		return $this;
	}

	public function render(): void
	{
		$this->template->noScripts = $this->noScripts;

		$this->template->render();
	}

}
