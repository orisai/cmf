<?php declare(strict_types = 1);

namespace OriCMF\Admin\Presenter;

use OriCMF\Auth\Admin\AdminFirewall;
use OriCMF\Login\Admin\LoginPresenter;
use OriCMF\UI\Control\Menu\Menu;
use OriCMF\UI\Presenter\BasePresenter;
use Orisai\Auth\Authentication\LogoutCode;
use function Orisai\Localization\t;

abstract class BaseAdminPresenter extends BasePresenter
{

	protected AdminFirewall $firewall;

	private Menu $menu;

	public function setAdmin(AdminFirewall $firewall, Menu $menu): void
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

	public function getFirewall(): AdminFirewall
	{
		return $this->firewall;
	}

	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this->setLayout(__DIR__ . '/@layout.latte');
		$this->template->menu = $this->menu;

		$head = $this['document-head'];
		$head['meta']->setRobots(['noindex', 'nofollow']);
		$head['title']->setModule(t('ori.cmf.admin.title'));
	}

}
