<?php

namespace App;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('<address>', 'Post:show');
		$router[] = new Route('category/<address>', 'Post:category');
		$router[] = new Route('<presenter>/<action>', 'Homepage:default');
		return $router;
	}

}

//'[/<page>]': 'Homepage:default'
//'clanok/<pageId>': 'Homepage:show'
//'kategoria/<tag>[/<page>]': 'Homepage:tag'
//'<presenter>/<action>': Homepage:default
