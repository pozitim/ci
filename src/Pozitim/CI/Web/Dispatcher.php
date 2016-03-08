<?php

namespace Pozitim\CI\Web;

use OU\DI;

class Dispatcher
{
    /**
     * @var DI
     */
    protected $di;
    protected $matchedRoute;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    public function dispatch()
    {
        $target = $this->getMatchedTarget();
        $className = $target[0];
        $actionName = $target[1];
        $controller = new $className($this->getDi());
        call_user_func_array([$controller, $actionName], $this->getMatchedNamedParams());
    }

    /**
     * @return array
     */
    protected function getMatchedTarget()
    {
        $target = $this->getMatchedRoute()['target'];
        $target = explode('#', $target);
        return [$target[0], $target[1]];
    }

    /**
     * @return array
     */
    protected function getMatchedNamedParams()
    {
        return $this->getMatchedRoute()['params'];
    }

    /**
     * @return array
     */
    protected function getMatchedRoute()
    {
        if ($this->matchedRoute == null) {
            $this->matchedRoute = $this->getRouter()->match();
        }
        return $this->matchedRoute;
    }

    /**
     * @return \AltoRouter
     * @throws \Exception
     */
    protected function getRouter()
    {
        return $this->getDi()->get('router');
    }

    /**
     * @return DI
     */
    protected function getDi()
    {
        return $this->di;
    }
}
