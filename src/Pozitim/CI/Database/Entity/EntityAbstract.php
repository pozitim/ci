<?php

namespace Pozitim\CI\Database\Entity;

use OU\DI;

abstract class EntityAbstract
{
    /**
     * @var DI
     */
    private $di;

    /**
     * EntityAbstract constructor.
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    /**
     * @return DI
     */
    public function getDi()
    {
        return $this->di;
    }
}
