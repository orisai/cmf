<?php declare(strict_types = 1);

namespace OriCMF\UI\Routing;

use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;
use OriCMF\Error\Admin\ErrorPresenter as AdminErrorPresenter;
use OriCMF\Error\Public\ErrorPresenter as PublicErrorPresenter;
use OriCMF\Home\Admin\HomePresenter as AdminHomePresenter;
use OriCMF\Home\Public\HomePresenter as PublicHomePresenter;
use OriCMF\Login\Admin\LoginPresenter as AdminLoginPresenter;
use OriCMF\Login\Public\LoginPresenter as PublicLoginPresenter;
use OriCMF\Robots\RobotsPresenter;

final class CmfUIRouterProvider implements RouterProvider
{

	public function getRouter(): Router
	{
		$router = new RouteList();

		$router->add(new ClassRoute('/admin/login', AdminLoginPresenter::class));
		$router->add(new ClassRoute('/admin/error', AdminErrorPresenter::class));
		$router->add(new ClassRoute('/admin', AdminHomePresenter::class));

		$router->add(new ClassRoute('/login', PublicLoginPresenter::class));
		$router->add(new ClassRoute('/error', PublicErrorPresenter::class));
		$router->add(new ClassRoute('/', PublicHomePresenter::class));

		$router->add(new ClassRoute('/robots.txt', RobotsPresenter::class));

		return $router;
	}

}
