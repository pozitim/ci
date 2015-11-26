<?php

namespace Pozitim\CI\Docker\Compose\Service;

class MySQLServiceParser implements ServiceParser
{
    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function parse(array $suiteConfigs)
    {
        return [
            'image' => 'mysql',
            'environment' => [
                'MYSQL_DATABASE' => $suiteConfigs['services']['mysql']['database'],
                'MYSQL_ALLOW_EMPTY_PASSWORD' => 'yes'
            ]
        ];
    }
}
