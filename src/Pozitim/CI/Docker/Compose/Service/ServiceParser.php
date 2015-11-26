<?php

namespace Pozitim\CI\Docker\Compose\Service;

interface ServiceParser
{
    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function parse(array $suiteConfigs);
}
