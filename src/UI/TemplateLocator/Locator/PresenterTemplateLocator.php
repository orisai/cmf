<?php declare(strict_types = 1);

namespace OriCMF\UI\TemplateLocator\Locator;

use Nette\Application\UI\Presenter;
use OriCMF\Admin\Presenter\BaseAdminPresenter;
use OriCMF\Public\Presenter\BasePublicPresenter;
use OriCMF\UI\Presenter\BasePresenter;
use OriCMF\UI\TemplateLocator\Exception\NoTemplateFound;

final class PresenterTemplateLocator extends BaseComponentTemplateLocator
{

	private const BreakClasses = [
		Presenter::class,
		BasePresenter::class,
		BaseAdminPresenter::class,
		BasePublicPresenter::class,
	];

	/**
	 * @throws NoTemplateFound
	 */
	public function getLayoutTemplatePath(Presenter $presenter, string $layoutName): string
	{
		return $this->getPath(
			$presenter,
			"@$layoutName",
			'Presenter',
			self::BreakClasses,
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
			'',
			self::BreakClasses,
			Presenter::DEFAULT_ACTION,
		);
	}

}
