<?php

include dirname(__FILE__).'/../vendor/autoload.php';

use gamringer\Router\Router;
use gamringer\Router\Dispatcher;
use gamringer\Router\Exception;
use gamringer\Router\Route;
use gamringer\Router\Routeable;
use gamringer\Router\Routeability;

class RouteableClass implements Routeable
{
	use Routeability;

	public $path;

	public function __construct($path)
	{
		$this->path = (string) $path;
	}
}

class SomeController
{
	public function dynamicMethod()
	{
		echo 'This is a dynamic Method'.PHP_EOL;
	}
	public static function staticMethod()
	{
		echo 'This is a static Method'.PHP_EOL;
	}
}

$routeable = new RouteableClass('/path/to/some/thing');
$router = new Router();
$dispatcher = new Dispatcher($router);

/**
 *  Set Dispatcher Rules
 */
$dispatcher->addRule(function($destination){
	if ($destination instanceof \Closure) {
		return $destination;
	}

	return false;
});
$dispatcher->addRule(function($destination){
	if (preg_match('/^(?<class>[\w\\\\]+)(?<type>\-\>|::)(?<method>[\w\\\\]+)$/', $destination, $match)) {
		$return = [];
		if ($match['type'] == '->') {
			$return[] = new $match['class']();
		
		}elseif ($match['type'] == '::') {
			$return[] = $match['class'];
		}
		
		$return[] = $match['method'];

		return $return;
	}
	
	return false;
});

/**
 *  Set Router Scope
 */
$router->setScope(function(RouteableClass $request){
	return $request->path;
});


/**
 *  Call Closure
 */
$router->clearRoutes();
$router->addRoute(new Route('bar', '.*',
	function(Routeable $request){
		echo 'This is a [Closure]' . PHP_EOL;
	},
	['var'=>'abc']
));
$dispatcher->dispatch($routeable);

/**
 *  Call Static Method in Class
 */
$router->clearRoutes();
$router->addRoute(new Route('bar', '.*',
	'SomeController::staticMethod',
	['var'=>'abc']
));
$dispatcher->dispatch($routeable);

/**
 *  Call Dynamic Method in Object
 */
$router->clearRoutes();
$router->addRoute(new Route('bar', '.*',
	'SomeController->dynamicMethod',
	['var'=>'abc']
));
$dispatcher->dispatch($routeable);


/**
 *  Call Unknown path
 */
$router->clearRoutes();
$router->addRoute(new Route('bar', '.*',
	'unknown path',
	['var'=>'abc']
));

try {
	$dispatcher->dispatch($routeable);
	
} catch(Exception $e) {
	echo $e->getMessage() . PHP_EOL;
}
