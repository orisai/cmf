<?php declare(strict_types = 1);

namespace OriCMF\UI\Routing;

use Nette\Routing\Router;

interface RouterProvider
{

	public function getRouter(): Router;

}
