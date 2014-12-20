<?php

namespace gamringer\Router\Test\Resources;

use \gamringer\Router;

class Routeable implements Router\Routeable
{
	use Router\Routeability;

	public $value;

	public function __construct($value)
	{
		$this->value = $value;
	}
}
