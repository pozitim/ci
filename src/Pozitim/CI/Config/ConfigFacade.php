<?php

namespace Pozitim\CI\Config;

use Symfony\Component\Yaml\Yaml;

class ConfigFacade
{
    /**
     * @param $filePath
     * @return array
     */
    public function parseFromFile($filePath)
    {
        $configs = (array) Yaml::parse(file_get_contents($filePath));
        $parser = new CompositeParser();
        $parser->addParser(new SuiteParser());
        $parser->addParser(new OsParser());
        return $parser->parse($configs);
    }
}
