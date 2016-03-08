<?php

namespace Pozitim\CI\Database\MySQL;

use OU\DI;
use Pozitim\MySQL\PDOHelper;

class MySQLAbstract
{
    /**
     * @var DI
     */
    private $di;

    /**
     * MySQLAbstract constructor.
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    /**
     * @return \PDO
     * @throws \Exception
     */
    protected function getPDO()
    {
        return $this->getDi()->get('pdo');
    }

    /**
     * @return PDOHelper
     */
    protected function getPDOHelper()
    {
        return $this->getDi()->get('pdo_helper');
    }

    /**
     * @return DI
     */
    protected function getDi()
    {
        return $this->di;
    }
}
