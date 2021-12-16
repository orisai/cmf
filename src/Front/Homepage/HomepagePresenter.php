<?php declare(strict_types = 1);

namespace OriCMF\Front\Homepage;

use OriCMF\Front\Base\Presenter\BaseFrontPresenter;
use OriCMF\UI\Presenter\ActionLink;

/**
 * @property-read HomepageTemplate $template
 */
final class HomepagePresenter extends BaseFrontPresenter
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
