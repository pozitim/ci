<?php

namespace Pozitim\CI\Config;

class ConfigFacadeTest extends \PHPUnit_Framework_TestCase
{
    public function testParseFromFile()
    {
        $facade = new ConfigFacade();
        $configs = $facade->parseFromFile(realpath(__DIR__) . '/sample.pozitim-ci.yml');
        $expected = [
            'suites' => [
                'php54' => [
                    'image' => 'centos-php54',
                    'env' => ['APPLICATION_ENV' => 'pozitim-ci'],
                    'services' => [
                        'mysql' => [
                            'database' => 'project'
                        ],
                        'memcached' => null,
                        'gearmand' => null,
                        'web' => null
                    ],
                    'scripts' => ['sh /project/tests/pozitim-ci-files/install.sh']
                ],
                'php55' => [
                    'image' => 'centos-php55',
                    'env' => ['APPLICATION_ENV' => 'pozitim-ci'],
                    'services' => [
                        'mysql' => [
                            'database' => 'project'
                        ],
                        'memcached' => null,
                        'gearmand' => null,
                        'web' => null
                    ],
                    'scripts' => ['sh /project/tests/pozitim-ci-files/install.sh']
                ],
                'php56' => [
                    'image' => 'centos-php56',
                    'env' => ['APPLICATION_ENV' => 'pozitim-ci'],
                    'services' => [
                        'mysql' => [
                            'database' => 'project'
                        ],
                        'memcached' => null,
                        'gearmand' => null,
                        'web' => null
                    ],
                    'scripts' => ['sh /project/tests/pozitim-ci-files/install.sh']
                ]
            ]
        ];
        $this->assertEquals($expected, $configs);
    }
}
