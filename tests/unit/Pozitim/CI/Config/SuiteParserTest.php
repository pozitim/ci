<?php

namespace Pozitim\CI\Config;

class SuiteParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSuitesKeyNotFound()
    {
        $configs = [];
        $parser = new SuiteParser();
        $this->setExpectedException('Pozitim\CI\Config\Exception\CouldNotParseException');
        $parser->parse($configs);
    }

    public function testParse()
    {
        $configs = [
            'suites' => [
                'suite1' => [
                    'extend' => 'suite3',
                    'env' => [
                        'APPLICATION_ENV' => 'pozitim-ci'
                    ],
                    'php-versions' => ['5.4']
                ],
                'suite2' => [
                    'extend' => 'suite1',
                    'php-versions' => ['5.5', '5.6']
                ],
                'suite3' => [
                    'services' => [
                        'memcached', 'gearmand'
                    ]
                ]
            ]
        ];
        $parser = new SuiteParser();
        $suite2 = $parser->parse($configs);
        $expected = [
            'suites' => [
                'suite1' => [
                    'env' => [
                        'APPLICATION_ENV' => 'pozitim-ci'
                    ],
                    'php-versions' => ['5.4'],
                    'services' => [
                        'memcached', 'gearmand'
                    ]
                ],
                'suite2' => [
                    'env' => [
                        'APPLICATION_ENV' => 'pozitim-ci'
                    ],
                    'php-versions' => ['5.5', '5.6'],
                    'services' => [
                        'memcached', 'gearmand'
                    ]
                ],
                'suite3' => [
                    'services' => [
                        'memcached', 'gearmand'
                    ]
                ]
            ]
        ];
        $this->assertEquals($expected, $suite2);
    }
}
