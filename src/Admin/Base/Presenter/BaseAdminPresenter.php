<?php declare(strict_types = 1);

namespace OriCMF\Admin\Base\Presenter;

use OriCMF\Admin\Auth\AdminFirewall;
use OriCMF\Admin\Login\LoginPresenter;
use OriCMF\UI\Presenter\Base\BasePresenter;

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
		if ($expired !== null && $expired->getLogoutReason() === $this->firewall::REASON_INACTIVITY) {
			$this->flashMessage($this->translator->translate('ori.ui.login.logout.reason.inactivity'));
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

		$this['document-head-meta']->setRobots(['noindex', 'nofollow']);
		$this['document-head-title']->setModule($this->translator->translate('ori.ui.admin.title'));
	}

}
