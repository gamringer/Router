<?php

namespace gamringer\Router\Test;

use \gamringer\Router\Exception;
use \gamringer\Router\Router;
use \gamringer\Router\Dispatcher;
use \gamringer\Router\Route;
use \gamringer\Router\Test\Resources\Routeable;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->destinationResult = 'baz';
    }

    /**
     * Tests that the route returns proper constructor arguments
     */
    public function testProperConstructor()
    {
        $router = new Router();
        $scope = function(Routeable $routeable){
            return $routeable->value;
        };

        $router->setScope($scope);

        $destinationResult = $this->destinationResult;

        $name = 'foo';
        $pattern = '(?<foo>foo)';
        $destination = function($foo) use ($destinationResult){
            return $destinationResult;
        };
        $data = ['foo'=>'foo'];
        $route = new Route($name, $pattern, $destination);
        $router->addRoute($route);

        $dispatcher = new Dispatcher($router);

        $rules = $dispatcher->getRules();

        $this->assertInternalType('array', $rules);
        $this->assertEmpty($rules);

        return $dispatcher;
    }

    /**
     * Tests that the route returns proper constructor arguments
     *
     * @depends testProperConstructor
     */
    public function testRuleManipulations($dispatcher)
    {
        $rule = function($destination){
            if ($destination instanceof \Closure) {
                return $destination;
            }

            return false;
        };
        $dispatcher->addRule($rule);
        $rules = $dispatcher->getRules();
        $this->assertInternalType('array', $rules);
        $this->assertNotEmpty($rules);
        $this->assertSame($rule, $rules[0]);

        $dispatcher->clearRules();
        $rules = $dispatcher->getRules();
        $this->assertInternalType('array', $rules);
        $this->assertEmpty($rules);

        $dispatcher->addRule($rule);

        return $dispatcher;
    }

    /**
     * Tests dispatching
     *
     * @depends testRuleManipulations
     */
    public function testDispatches($dispatcher)
    {
        $routeable = new Routeable('foo');
        $result = $dispatcher->dispatch($routeable);
        $this->assertEquals($this->destinationResult, $result);
    }

    /**
     * Tests dispatching
     *
     * @expectedException Exception
     *
     * @depends testRuleManipulations
     */
    public function testDoesntDispatch($dispatcher)
    {
        $routeable = new Routeable('foo');
        $dispatcher->clearRules();
        $dispatcher->dispatch($routeable);
    }
}
