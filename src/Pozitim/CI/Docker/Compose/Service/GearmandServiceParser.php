<?php

namespace Pozitim\CI\Docker\Compose\Service;

class GearmandServiceParser implements ServiceParser
{
    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function parse(array $suiteConfigs)
    {
        return [
            'image' => 'pataquest/gearmand'
        ];
    }
}
