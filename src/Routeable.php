<?php

namespace gamringer\Router;

interface Routeable
{
    public function getAttributes();

    public function setAttributes(Array $attributes);

    public function addAttributes(Array $attributes);

    public function discover(Ventureable $route, callable $scope);
}
