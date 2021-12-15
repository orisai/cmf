<?php declare(strict_types = 1);

namespace OriCMF\UI\Front\Login;

use Nette\Application\Attributes\Persistent;
use OriCMF\UI\Front\Base\Presenter\BaseFrontPresenter;
use OriCMF\UI\Presenter\ActionLink;
use OriCMF\UI\Presenter\NoLogin;

/**
 * @property-read LoginTemplate $template
 */
class LoginPresenter extends BaseFrontPresenter
{

	use NoLogin;

	#[Persistent]
	public string $backlink = '';

	public static function createLink(string $backlink = ''): ActionLink
	{
		return ActionLink::fromClass(self::class, [
			'backlink' => $backlink,
		]);
	}

	public function action(): void
	{
		// Action method
	}

}
