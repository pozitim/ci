<?php

namespace Pozitim\CI\Docker\Compose\Service;

use Pozitim\CI\Suite;

abstract class ServiceAbstract implements Service
{
    /**
     * @var Suite
     */
    protected $suite;

    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @var array
     */
    protected $serviceConfigs = array();

    /**
     * @param $keyName
     * @param null $default
     * @return mixed
     */
    public function getServiceConfigValue($keyName, $default = null)
    {
        if (!$this->isServiceConfigKeyExists($keyName)) {
            return $default;
        }
        return $this->getServiceConfigs()[$keyName];
    }

    /**
     * @param $keyName
     * @return bool
     */
    public function isServiceConfigKeyExists($keyName)
    {
        return array_key_exists($keyName, $this->getServiceConfigs());
    }

    /**
     * @return Suite
     */
    public function getSuite()
    {
        return $this->suite;
    }

    /**
     * @param Suite $suite
     */
    public function setSuite(Suite $suite)
    {
        $this->suite = $suite;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return array
     */
    public function getServiceConfigs()
    {
        return $this->serviceConfigs;
    }

    /**
     * @param array $serviceConfigs
     */
    public function setServiceConfigs(array $serviceConfigs)
    {
        $this->serviceConfigs = $serviceConfigs;
    }
}
