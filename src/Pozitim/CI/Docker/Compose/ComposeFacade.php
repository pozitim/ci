<?php

namespace Pozitim\CI\Docker\Compose;

use Pozitim\CI\Docker\Compose\Service\ServiceParser;

class ComposeFacade
{
    /**
     * @param array $configs
     * @return array
     */
    public function generateFiles(array $configs)
    {
        $composeFiles = [];
        foreach ($configs['suites'] as $suiteName => $suiteConfigs) {
            $composeFiles[$suiteName] = $this->generateFileForSuite($suiteConfigs);
        }
        return $composeFiles;
    }

    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function generateFileForSuite(array $suiteConfigs)
    {
        $composeFile = [];
        foreach ($this->getServiceNames($suiteConfigs) as $serviceName) {
            $composeFile[$serviceName] = $this->getServiceParser($serviceName)->parse($suiteConfigs);
        }
        return $composeFile;
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
