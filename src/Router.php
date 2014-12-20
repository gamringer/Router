<?php

namespace gamringer\Router;

class Router implements Routes
{
    const ROUTE_NO_ATTRIBUTES = 0;
    const ROUTE_ADD_ATTRIBUTES = 0b1;
    const ROUTE_SET_ATTRIBUTES = 0b10;

    protected $routes = [];
    protected $scope;

    public function setScope(callable $scope)
    {
        $this->scope = $scope;
    }

    public function clearRoutes()
    {
        $this->routes = [];
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function addRoute(Ventureable $route)
    {
        $this->routes[$route->getName()] = $route;

        return $this;
    }

    public function route(Routeable $request, $mode = 0)
    {
        foreach ($this->routes as $route) {
            if ($request->discover($route, $this->scope, $mode)) {
                return $route;
            }
        }

        throw new Exception('No [Route] found to match the [Routeable]');
    }
}
