<?php declare(strict_types = 1);

namespace OriCMF\UI\TemplateLocator\Locator;

use Nette\Application\UI\Control;
use OriCMF\UI\TemplateLocator\Exception\NoTemplateFound;

final class ControlTemplateLocator extends BaseTemplateLocator
{

	public const DefaultViewName = 'default';

	/**
	 * @throws NoTemplateFound
	 */
	public function getTemplatePath(Control $control, string $viewName): string
	{
		return $this->getPath(
			$control,
			$viewName,
			'Control',
			Control::class,
			self::DefaultViewName,
		);
	}

}
