<?php

namespace Pozitim\Console;

use OU\DI;
use Symfony\Component\Console\Helper\Helper;

class DiHelper extends Helper
{
    /**
     * @var DI
     */
    protected $dependencyInjection;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    /**
     * @return Di
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     *
     * @api
     */
    public function getName()
    {
        return 'di';
    }
}
