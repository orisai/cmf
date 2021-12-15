<?php declare(strict_types = 1);

namespace OriCMF\UI\Routing;

use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;
use OriCMF\UI\Admin\Dashboard\DashboardPresenter;
use OriCMF\UI\Admin\Error\ErrorPresenter as AdminErrorPresenter;
use OriCMF\UI\Admin\Login\LoginPresenter as AdminLoginPresenter;
use OriCMF\UI\Front\Error\ErrorPresenter as FrontErrorPresenter;
use OriCMF\UI\Front\Homepage\HomepagePresenter;
use OriCMF\UI\Front\Login\LoginPresenter as FrontLoginPresenter;
use OriCMF\UI\Robots\RobotsPresenter;

final class CmfUIRouterProvider implements RouterProvider
{

	public function getRouter(): Router
	{
		$router = new RouteList();

		$router->add(new ClassRoute('/admin/login', AdminLoginPresenter::class));
		$router->add(new ClassRoute('/admin/error', AdminErrorPresenter::class));
		$router->add(new ClassRoute('/admin', DashboardPresenter::class));

		$router->add(new ClassRoute('/login', FrontLoginPresenter::class));
		$router->add(new ClassRoute('/error', FrontErrorPresenter::class));
		$router->add(new ClassRoute('/', HomepagePresenter::class));

		$router->add(new ClassRoute('/robots.txt', RobotsPresenter::class));

		return $router;
	}

}
