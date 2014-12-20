<?php

namespace gamringer\Router;

trait Routeability
{
    protected $attributes = [];

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes(Array $attributes)
    {
        $this->attributes = [];
        $this->addAttributes($attributes);

        return $this;
    }

    public function addAttributes(Array $attributes)
    {
        $this->attributes = array_merge_recursive($this->attributes, $attributes);

        return $this;
    }

    public function discover(Ventureable $route, callable $scope, $mode)
    {
        $extract = null;
        if ($route->match($scope($this), $extract)) {
            $attributes = array_filter($extract, function($key){
                return !is_integer($key);
            }, ARRAY_FILTER_USE_KEY);

            switch ($mode) {
                case Router::ROUTE_NO_ATTRIBUTES:
                    break;

                case Router::ROUTE_SET_ATTRIBUTES:
                    $this->setAttributes($attributes);
                    break;
                
                case Router::ROUTE_ADD_ATTRIBUTES:
                    $this->addAttributes($attributes);
                    break;
            }

            return true;
        }

        return false;
    }
}
