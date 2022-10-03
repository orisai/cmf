<?php declare(strict_types = 1);

namespace OriCMF\UI\TemplateLocator\Template;

use OriCMF\UI\TemplateLocator\Locator\ControlTemplateLocator;

interface FlexibleControlTemplate
{

	/**
	 * @internal
	 */
	public function setTemplateLocator(ControlTemplateLocator $templateLocator): void;

	public function setView(string $view): void;

}
