<?php

namespace Pozitim\CI\Docker\Compose;

use Pozitim\CI\Docker\Compose\Service\Service;
use Pozitim\CI\Suite;

class ServiceBuilder
{
    /**
     * @param Suite $suite
     * @param $serviceName
     * @param array $serviceConfigs
     * @return Service
     */
    public static function build(Suite $suite, $serviceName, array $serviceConfigs)
    {
        /**
         * @var Service $service
         */
        $className = 'Pozitim\CI\Docker\Compose\Service\\' . ucwords($serviceName) . 'ServiceImpl';
        $service = new $className();
        $service->setSuite($suite);
        $service->setServiceName($serviceName);
        $service->setServiceConfigs($serviceConfigs);
        return $service;
    }
}
