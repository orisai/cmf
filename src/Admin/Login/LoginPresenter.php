<?php declare(strict_types = 1);

namespace OriCMF\Admin\Login;

use Nette\Application\Attributes\Persistent;
use OriCMF\Admin\Presenter\BaseAdminPresenter;
use OriCMF\UI\ActionLink;
use OriCMF\UI\Presenter\NoLogin;

/**
 * @property-read LoginTemplate $template
 */
class LoginPresenter extends BaseAdminPresenter
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
