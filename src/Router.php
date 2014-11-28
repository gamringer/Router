<?php

namespace gamringer\Router;

class Router implements Routes
{

    protected $routes = [];
    protected $scope;

    public function setScope(callable $scope)
    {
        $this->scope = $scope->bindTo($this);
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
    
    public function route(Routeable $request)
    {
        foreach ($this->routes as $route) {
            if ($request->discover($route, $this->scope)) {
                return $route;
            }
        }

        throw new Exception('No [Route] found to match the [Routeable]');
    }
}
