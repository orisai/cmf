<?php declare(strict_types = 1);

namespace OriCMF\Home\Public;

use OriCMF\Public\Presenter\BasePublicPresenter;
use OriCMF\UI\ActionLink;

/**
 * @property-read HomePresenterTemplate $template
 */
final class HomePresenter extends BasePublicPresenter
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
