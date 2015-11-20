<?php

namespace Pozitim\CI\Config;

class CompositeParser implements Parser
{
    /**
     * @var array
     */
    protected $parsers = [];

    /**
     * @param Parser $parser
     */
    public function addParser(Parser $parser)
    {
        $this->parsers[] = $parser;
    }

    /**
     * @param array $configs
     * @return array
     */
    public function parse(array $configs = [])
    {
        /**
         * @var Parser $parser
         */
        foreach ($this->parsers as $parser) {
            $configs = $parser->parse($configs);
        }
        return $configs;
    }
}
