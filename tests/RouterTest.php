<?php

namespace gamringer\Router\Test;

use \gamringer\Router\Exception;
use \gamringer\Router\Router;
use \gamringer\Router\Route;
use \gamringer\Router\Test\Resources\Routeable;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the route returns proper constructor arguments
     */
    public function testProperConstructor()
    {
        $router = new Router();
        $routes = $router->getRoutes();

        $this->assertInternalType('array', $routes);
        $this->assertEmpty($routes);

        return $router;
    }

    /**
     * Tests that the routes are properly added
     *
     * @depends testProperConstructor
     */
    public function testAddRoutes($router)
    {
        $name = 'foo';
        $pattern = '(?<foo>foo)';
        $destination = function($foo){};
        $data = ['foo'=>'foo'];
        $route = new Route($name, $pattern, $destination);

        $router->addRoute($route);
        $routes = $router->getRoutes();

        $this->assertArrayHasKey($name, $routes);
        $this->assertSame($route, $routes[$name]);

        return $router;
    }

    /**
     * Tests that the router properly routes the Routeable
     *
     * @depends testAddRoutes
     */
    public function testRoutes($router)
    {
        $routeable = new Routeable('foo');
        $scope = function(Routeable $routeable){
            return $routeable->value;
        };

        $router->setScope($scope);

        $route = $router->route($routeable);
        $this->assertSame($route, $router->getRoutes()['foo']);

        $routeable->setAttributes([]);
        $route = $router->route($routeable, Router::ROUTE_ADD_ATTRIBUTES);
        $this->assertArrayHasKey('foo', $routeable->getAttributes());

        $routeable->setAttributes([]);
        $route = $router->route($routeable, Router::ROUTE_SET_ATTRIBUTES);
        $this->assertArrayHasKey('foo', $routeable->getAttributes());


        return $router;
    }

    /**
     * Tests that the router properly not routes the invalid Routeable
     *
     * @expectedException Exception
     *
     * @depends testAddRoutes
     */
    public function testDoesntRoute($router)
    {
        $routeable = new Routeable('bar');
        $route = $router->route($routeable);

        return $router;
    }

    /**
     * Tests that the routes are properly cleared
     *
     * @depends testRoutes
     */
    public function testClearRoutes($router)
    {
        $this->assertNotEmpty($router->getRoutes());

        $router->clearRoutes();

        $routes = $router->getRoutes();

        $this->assertInternalType('array', $routes);
        $this->assertEmpty($routes);

        return $router;
    }
}
