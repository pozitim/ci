<?php

namespace Pozitim\CI\Docker\Compose\Service;

use Pozitim\CI\Suite;

interface Service
{
    /**
     * @return Suite
     */
    public function getSuite();

    /**
     * @param Suite $suite
     */
    public function setSuite(Suite $suite);

    /**
     * @return string
     */
    public function getServiceName();

    /**
     * @param $serviceName
     */
    public function setServiceName($serviceName);

    /**
     * @return array
     */
    public function getServiceConfigs();

    /**
     * @param array $serviceConfigs
     */
    public function setServiceConfigs(array $serviceConfigs);

    /**
     * @return array
     */
    public function getDockerComposeContent();
}
