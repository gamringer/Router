<?php

namespace gamringer\Router;

interface Routes
{
    public function getRoutes();
    
    public function clearRoutes();

    public function addRoute(Ventureable $route);

    public function route(Routeable $request);
}
