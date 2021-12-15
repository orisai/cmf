<?php declare(strict_types = 1);

namespace OriCMF\UI\Admin\Dashboard;

use OriCMF\UI\Admin\Base\Presenter\BaseAdminPresenter;
use OriCMF\UI\Presenter\ActionLink;

/**
 * @property-read DashboardTemplate $template
 */
final class DashboardPresenter extends BaseAdminPresenter
{

	public function action(): void
	{
		// Action method
	}

	public static function createLink(): ActionLink
	{
		return ActionLink::fromClass(self::class);
	}

}
