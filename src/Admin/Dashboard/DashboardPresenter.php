<?php declare(strict_types = 1);

namespace OriCMF\Admin\Dashboard;

use OriCMF\Admin\Presenter\BaseAdminPresenter;
use OriCMF\UI\ActionLink;

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
