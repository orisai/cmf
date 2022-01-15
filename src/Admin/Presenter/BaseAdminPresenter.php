<?php declare(strict_types = 1);

namespace OriCMF\Admin\Presenter;

use OriCMF\Admin\Auth\AdminFirewall;
use OriCMF\Admin\Login\LoginPresenter;
use OriCMF\UI\Presenter\BasePresenter;

abstract class BaseAdminPresenter extends BasePresenter
{

	public const LAYOUT_PATH = __DIR__ . '/@layout.latte';

	protected AdminFirewall $firewall;

	public function injectAdmin(AdminFirewall $firewall): void
	{
		$this->firewall = $firewall;
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
		if ($expired !== null && $expired->getLogoutCode() === $this->firewall::LOGOUT_INACTIVITY) {
			$this->flashMessage($this->translator->translate('ori.login.logout.reason.inactivity'));
		}

		$this->actionRedirect(LoginPresenter::createLink(
			$this->storeRequest(),
		));
	}

	public function handleLogout(): void
	{
		$this->firewall->logout();

		if (!$this->isLoginRequired()) {
			$this->redirect('this');
		} else {
			$this->actionRedirect(LoginPresenter::createLink());
		}
	}

	public function getFirewall(): AdminFirewall
	{
		return $this->firewall;
	}

	protected function beforeRender(): void
	{
		parent::beforeRender();

		$head = $this['document-head'];
		$head['meta']->setRobots(['noindex', 'nofollow']);
		$head['title']->setModule($this->translator->translate('ori.admin.title'));
	}

}