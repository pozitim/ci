<?php

namespace Pozitim\CI\Config;

use Pozitim\CI\Config\Exception\CouldNotParseException;

class SuiteParser implements Parser
{
    /**
     * @param array $configs
     * @return array
     * @throws CouldNotParseException
     */
    public function parse(array $configs)
    {
        if (!isset($configs['suites'])) {
            throw new CouldNotParseException('suites key not found in the yaml file.');
        }
        return $this->extend($configs);
    }

    /**
     * @param array $configs
     * @return array
     */
    public function extend(array $configs)
    {
        $tmp = [];
        foreach ($configs['suites'] as $suiteName => $suiteConfigs) {
            if (isset($suiteConfigs['extend'])) {
                $tmp[$suiteName] = $this->merge($suiteName, $suiteConfigs['extend'], $configs);
            } else {
                $tmp[$suiteName] = $suiteConfigs;
            }
        }
        $configs['suites'] = $tmp;
        return $configs;
    }

    /**
     * @param $suiteName
     * @param $extendName
     * @param array $configs
     * @return array
     */
    public function merge($suiteName, $extendName, array $configs)
    {
        $extendConfigs = $configs['suites'][$extendName];
        if (isset($extendConfigs['extend'])) {
            $extendConfigs = $this->merge($extendName, $extendConfigs['extend'], $configs);
        }
        $configs = array_merge($extendConfigs, $configs['suites'][$suiteName]);
        unset($configs['extend']);
        return $configs;
    }
}
