<?php declare(strict_types = 1);

namespace OriCMF\Home\Admin;

use OriCMF\Admin\Presenter\BaseAdminPresenter;
use OriCMF\UI\ActionLink;

/**
 * @property-read HomePresenterTemplate $template
 */
final class HomePresenter extends BaseAdminPresenter
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
