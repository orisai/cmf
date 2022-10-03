<?php declare(strict_types = 1);

namespace OriCMF\UI\TemplateLocator\Locator;

use Nette\Application\UI\Presenter;
use OriCMF\UI\TemplateLocator\Exception\NoTemplateFound;

final class PresenterTemplateLocator extends BaseTemplateLocator
{

	/**
	 * @throws NoTemplateFound
	 */
	public function getLayoutTemplatePath(Presenter $presenter, string $layoutName): string
	{
		return $this->getPath(
			$presenter,
			"@$layoutName",
			'Presenter',
			Presenter::class,
			Presenter::DEFAULT_ACTION,
		);
	}

	/**
	 * @throws NoTemplateFound
	 */
	public function getActionTemplatePath(Presenter $presenter, string $viewName): string
	{
		return $this->getPath(
			$presenter,
			$viewName,
			'Presenter',
			Presenter::class,
			Presenter::DEFAULT_ACTION,
		);
	}

}
