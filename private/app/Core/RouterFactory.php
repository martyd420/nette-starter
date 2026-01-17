<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList();
		$router->withModule('Admin')
			->addRoute('admin/<presenter>/<action>[/<id>]', 'Dashboard:default');

		$router->withModule('Front')
			->addRoute('login', 'Login:default')
			->addRoute('register', 'Register:default')
			->addRoute('<presenter>/<action>[/<id>]', 'Home:default');

		return $router;
	}
}
