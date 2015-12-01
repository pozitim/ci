<?php

namespace Pozitim\CI\Docker\Compose;

use OU\DI;
use Pozitim\CI\Config\ConfigParser;
use Pozitim\CI\Docker\Compose\Service\ServiceParser;

class ComposeSettingsGenerator
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    /**
     * @param $filePath
     * @return array
     * @throws \Exception
     */
    public function generateSettingsFromFile($filePath)
    {
        /**
         * @var ConfigParser $configParser
         */
        $configParser = $this->di->get('config_parser');
        $projectConfigs = $configParser->parseFromFile($filePath);
        return $this->generateSettings($projectConfigs);
    }

    /**
     * @param array $projectConfigs
     * @return array
     */
    public function generateSettings(array $projectConfigs)
    {
        $composeSettings = [];
        foreach ($projectConfigs['suites'] as $suiteName => $suiteConfigs) {
            $composeSettings[$suiteName] = $this->generateSettingsForSuite($suiteConfigs);
        }
        return $composeSettings;
    }

    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function generateSettingsForSuite(array $suiteConfigs)
    {
        $composeSetting = [];
        foreach ($this->getServiceNames($suiteConfigs) as $serviceName) {
            $composeSetting[$serviceName] = $this->getServiceParser($serviceName)->parse($suiteConfigs);
        }
        return $composeSetting;
    }

    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function getServiceNames(array $suiteConfigs)
    {
        $services = isset($suiteConfigs['services']) ? $suiteConfigs['services'] : [];
        if (!array_key_exists('web', $services)) {
            $services['web'] = null;
        }
        return array_keys($services);
    }

    /**
     * @param $serviceName
     * @return ServiceParser
     */
    public function getServiceParser($serviceName)
    {
        $className = 'Pozitim\CI\Docker\Compose\Service\\' . ucfirst(strtolower(trim($serviceName))) . 'ServiceParser';
        return new $className();
    }
}
