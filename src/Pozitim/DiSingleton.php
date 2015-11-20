<?php

namespace Pozitim;

class DiSingleton
{
    /**
     * @var DiSingleton
     */
    protected static $instance;

    /**
     * @var \OU\DI
     */
    protected $di;

    /**
     * @return DiSingleton
     */
    public static function getInstance()
    {
        if (static::$instance == null) {
            static::$instance = new DiSingleton();
        }

        return static::$instance;
    }

    /**
     * @return \OU\DI
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param \OU\DI $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }
}
