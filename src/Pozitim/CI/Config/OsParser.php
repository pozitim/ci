<?php

namespace Pozitim\CI\Config;

class OsParser implements Parser
{
    /**
     * @param array $configs
     * @return array
     */
    public function parse(array $configs)
    {
        $tmp = [];
        foreach ($configs['suites'] as $suiteName => $suiteConfigs) {
            if (!isset($suiteConfigs['os'])) {
                $suiteConfigs['os'] = 'centos';
            }
            $tmp[$suiteName] = $suiteConfigs;
        }
        $configs['suites'] = $tmp;
        return $configs;
    }
}
