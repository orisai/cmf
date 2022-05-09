<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Head;

use OriCMF\UI\Control\BaseControl;
use OriCMF\UI\Control\Icons\IconsControl;
use OriCMF\UI\Control\Icons\IconsControlFactory;
use OriCMF\UI\Control\Links\LinksControl;
use OriCMF\UI\Control\Links\LinksControlFactory;
use OriCMF\UI\Control\Meta\MetaControl;
use OriCMF\UI\Control\Meta\MetaControlFactory;
use OriCMF\UI\Control\NoScript\NoScriptControl;
use OriCMF\UI\Control\NoScript\NoScriptControlFactory;
use OriCMF\UI\Control\Scripts\ScriptsControl;
use OriCMF\UI\Control\Scripts\ScriptsControlFactory;
use OriCMF\UI\Control\Styles\StylesControl;
use OriCMF\UI\Control\Styles\StylesControlFactory;
use OriCMF\UI\Control\Title\TitleControl;
use OriCMF\UI\Control\Title\TitleControlFactory;

/**
 * @property-read HeadTemplate $template
 */
class HeadControl extends BaseControl
{

	public function __construct(
		private readonly IconsControlFactory $iconsFactory,
		private readonly LinksControlFactory $linksFactory,
		private readonly MetaControlFactory $metaFactory,
		private readonly NoScriptControlFactory $noScriptFactory,
		private readonly TitleControlFactory $titleFactory,
		private readonly StylesControlFactory $stylesFactory,
		private readonly ScriptsControlFactory $scriptsFactory,
	)
	{
	}

	public function render(): void
	{
		$this->template->render();
	}

	protected function createComponentIcons(): IconsControl
	{
		return $this->iconsFactory->create();
	}

	protected function createComponentLinks(): LinksControl
	{
		return $this->linksFactory->create();
	}

	protected function createComponentMeta(): MetaControl
	{
		return $this->metaFactory->create();
	}

	protected function createComponentNoScript(): NoScriptControl
	{
		return $this->noScriptFactory->create();
	}

	protected function createComponentTitle(): TitleControl
	{
		return $this->titleFactory->create();
	}

	protected function createComponentStyles(): StylesControl
	{
		return $this->stylesFactory->create();
	}

	protected function createComponentScripts(): ScriptsControl
	{
		return $this->scriptsFactory->create();
	}

}
