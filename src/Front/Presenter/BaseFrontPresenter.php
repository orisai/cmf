<?php declare(strict_types = 1);

namespace OriCMF\Front\Presenter;

use OriCMF\Core\Config\BuildConfig;
use OriCMF\Front\Auth\FrontFirewall;
use OriCMF\Front\Login\LoginPresenter;
use OriCMF\UI\Control\Menu\Menu;
use OriCMF\UI\Presenter\BasePresenter;
use Orisai\Auth\Authentication\LogoutCode;

abstract class BaseFrontPresenter extends BasePresenter
{

	public const LayoutPath = __DIR__ . '/@layout.latte';

	public FrontFirewall $firewall;

	private Menu $menu;

	public function setFront(FrontFirewall $firewall, Menu $menu): void
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
			$this->flashMessage($this->translator->translate('ori.cmf.login.logout.reason.inactivity'));
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

	public function getFirewall(): FrontFirewall
	{
		return $this->firewall;
	}

	protected function beforeRender(): void
	{
		parent::beforeRender();

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
