<?php

namespace Pozitim\CI\Config;

interface Parser
{
    /**
     * @param array $configs
     * @return array
     */
    public function parse(array $configs);
}
