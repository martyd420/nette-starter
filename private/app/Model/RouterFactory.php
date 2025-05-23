<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;

		$router->withModule('AdminModule')
			->addRoute('admin/<presenter>/<action>[/<id>]', 'Home:default');

		$router->withModule('FrontModule')
			->addRoute('<presenter>/<action>[/<id>]', 'Home:default');

		return $router;
	}
}
