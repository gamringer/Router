<?php

namespace gamringer\Router\Test;

use \gamringer\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the route returns proper constructor arguments
     */
    public function testProperConstructor()
    {
        $name = 'foo';
        $pattern = '(?<foo>foo)';
        $destination = function($foo){};
        $data = ['foo'=>'foo'];
        $route = new Route($name, $pattern, $destination);

        $this->assertEquals($name, $route->getName());
        $this->assertEquals($pattern, $route->getPattern());
        $this->assertSame($destination, $route->getDestination());
        $this->assertNotSame($data, $route->getData());

        return $route;
    }

    /**
     * Tests that the route returns proper constructor arguments
     *
     * @depends testProperConstructor
     */
    public function testMatches($route)
    {
        $this->assertTrue($route->match('foo'));
        $this->assertFalse($route->match('bar'));

        $route->match('foo', $match);
        $this->assertEquals($match['foo'], 'foo');
    }
}
