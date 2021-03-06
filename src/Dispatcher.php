<?php

namespace gamringer\Router;

class Dispatcher
{
    protected $router;
    protected $rules = [];

    public function __construct(Routes $router)
    {
        $this->router = $router;
    }

    public function dispatch(Routeable $request)
    {
        $route = $this->router->route($request);

        $destination = $route->getDestination();
        $callable = $this->getCallable($destination);

        return $callable($request);
    }

    public function addRule(\Closure $rule)
    {
        $this->rules[] = $rule;
    }

    public function clearRules()
    {
        $this->rules = [];
    }

    public function getRules()
    {
        return $this->rules;
    }

    protected function getCallable($destination)
    {
        foreach ($this->rules as $rule) {
            $ruling = $rule($destination);
            if (is_callable($ruling)) {
                return $ruling;
            }
        }

        throw new Exception('[Dispatcher] could not dispatch [Routeable] to destination');
    }
}
