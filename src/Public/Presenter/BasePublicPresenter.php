<?php declare(strict_types = 1);

namespace OriCMF\Public\Presenter;

use OriCMF\App\Config\BuildConfig;
use OriCMF\Auth\Public\PublicFirewall;
use OriCMF\Login\Public\LoginPresenter;
use OriCMF\UI\Control\Menu\Menu;
use OriCMF\UI\Presenter\BasePresenter;
use Orisai\Auth\Authentication\LogoutCode;
use function Orisai\TranslationContracts\t;

abstract class BasePublicPresenter extends BasePresenter
{

	protected PublicFirewall $firewall;

	private Menu $menu;

	public function setPublic(PublicFirewall $firewall, Menu $menu): void
	{
		$this->firewall = $firewall;
		$this->menu = $menu;
	}

	protected function isLoginRequired(): bool
	{
		return true;
	}

	protected function checkUserIsLoggedIn(): void
	{
		if ($this->firewall->isLoggedIn()) {
			return;
		}

		$expired = $this->firewall->getLastExpiredLogin();
		if ($expired !== null && $expired->getLogoutCode()->name === LogoutCode::inactivity()->name) {
			$this->flashMessage(t('ori.cmf.login.logout.reason.inactivity'));
		}

		$this->redirectToAction(LoginPresenter::createLink(
			$this->storeRequest(),
		));
	}

	public function handleLogout(): void
	{
		$this->firewall->logout();

		if (!$this->isLoginRequired()) {
			$this->redirect('this');
		} else {
			$this->redirectToAction(LoginPresenter::createLink());
		}
	}

	public function getFirewall(): PublicFirewall
	{
		return $this->firewall;
	}

	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this->setLayout(__DIR__ . '/@layout.latte');
		$this->template->menu = $this->menu;

		$meta = $this['document-head-meta'];
		if ($this->config->get(BuildConfig::class)->isStable()) {
			$meta->setRobots(['index', 'follow']);
		} else {
			$this->getHttpResponse()->addHeader('X-Robots-Tag', 'none');
			$meta->setRobots(['nofollow', 'noindex']);
		}
	}

}
