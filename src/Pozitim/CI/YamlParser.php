<?php

namespace Pozitim\CI;

use Symfony\Component\Yaml\Yaml;

class YamlParser
{
    /**
     * @var array
     */
    protected $suites = array();

    /**
     * @param $projectFile
     * @return array
     */
    public function parse($projectFile)
    {
        $this->suites = Yaml::parse(file_get_contents($projectFile));
        foreach (array_keys($this->suites) as $suiteName) {
            $this->extendSuite($suiteName);
        }
        return $this->suites;
    }

    /**
     * @param $suiteName
     */
    protected function extendSuite($suiteName)
    {
        if (isset($this->suites[$suiteName]['services']) == false) {
            $this->suites[$suiteName]['services'] = array();
        } else {
            foreach ($this->suites[$suiteName]['services'] as $serviceName => $serviceConfigs) {
                if (is_array($serviceConfigs) == false) {
                    $this->suites[$suiteName]['services'][$serviceName] = array();
                }
            }
        }
        if (isset($this->suites[$suiteName]['services']['default']) == false) {
            $this->suites[$suiteName]['services']['default'] = array();
        }
        if (isset($this->suites[$suiteName]['extend']) == false) {
            return;
        }
        $sourceSuiteName = $this->suites[$suiteName]['extend'];
        $this->extendSuite($sourceSuiteName);
        $extendedConfigs = array_merge($this->suites[$sourceSuiteName], $this->suites[$suiteName]);
        $this->suites[$suiteName] = $extendedConfigs;
        unset($this->suites[$suiteName]['extend']);
    }
}
