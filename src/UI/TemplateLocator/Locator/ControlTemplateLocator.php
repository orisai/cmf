<?php declare(strict_types = 1);

namespace OriCMF\UI\TemplateLocator\Locator;

use Nette\Application\UI\Control;
use OriCMF\UI\Control\BaseControl;
use OriCMF\UI\TemplateLocator\Exception\NoTemplateFound;

final class ControlTemplateLocator extends BaseComponentTemplateLocator
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
			[
				Control::class,
				BaseControl::class,
			],
			self::DefaultViewName,
		);
	}

}
