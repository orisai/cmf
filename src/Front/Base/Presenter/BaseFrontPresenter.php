<?php declare(strict_types = 1);

namespace OriCMF\Front\Base\Presenter;

use OriCMF\Front\Auth\FrontFirewall;
use OriCMF\Front\Login\LoginPresenter;
use OriCMF\UI\Presenter\Base\BasePresenter;

abstract class BaseFrontPresenter extends BasePresenter
{

	public const LAYOUT_PATH = __DIR__ . '/@layout.latte';

	public FrontFirewall $firewall;

	public function injectFront(FrontFirewall $firewall): void
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

	public function getFirewall(): FrontFirewall
	{
		return $this->firewall;
	}

	protected function beforeRender(): void
	{
		parent::beforeRender();

		if ($this->applicationConfig->getBuildConfig()->isStable()) {
			$this['document-head-meta']->setRobots(['index', 'follow']);
		} else {
			$this->getHttpResponse()->addHeader('X-Robots-Tag', 'none');
			$this['document-head-meta']->setRobots(['nofollow', 'noindex']);
		}
	}

}
