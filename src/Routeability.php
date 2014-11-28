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

	public function discover(Ventureable $route, callable $scope)
	{
		$extract = null;
		if($route->match($scope($this), $extract)){
			$this->attributes = array_merge($extract, $this->attributes);

			return true;
		}

		return false;
	}
}
