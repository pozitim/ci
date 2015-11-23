<?php

namespace Pozitim\CI\Config;

class OsParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $configs = [
            'suites' => [
                'suite1' => [
                    'os' => 'centos'
                ],
                'suite2' => [
                    'os' => 'ubuntu',
                ],
                'suite3' => [
                ]
            ]
        ];
        $parser = new OsParser();
        $suite2 = $parser->parse($configs);
        $expected = [
            'suites' => [
                'suite1' => [
                    'os' => 'centos'
                ],
                'suite2' => [
                    'os' => 'ubuntu'
                ],
                'suite3' => [
                    'os' => 'centos'
                ]
            ]
        ];
        $this->assertEquals($expected, $suite2);
    }
}
