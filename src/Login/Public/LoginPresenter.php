<?php declare(strict_types = 1);

namespace OriCMF\Login\Public;

use Nette\Application\Attributes\Persistent;
use OriCMF\Public\Presenter\BasePublicPresenter;
use OriCMF\UI\ActionLink;
use OriCMF\UI\Presenter\NoLogin;

/**
 * @property-read LoginPresenterTemplate $template
 */
class LoginPresenter extends BasePublicPresenter
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
