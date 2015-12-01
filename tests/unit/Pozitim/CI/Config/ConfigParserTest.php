<?php

namespace Pozitim\CI\Config;

class ConfigParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSuitesKeyNotFound()
    {
        $configs = [];
        $filesystemHelper = $this->getMock('Pozitim\CI\Filesystem\Adapter');
        $parser = new ConfigParser($filesystemHelper);
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
        $filesystemHelper = $this->getMock('Pozitim\CI\Filesystem\Adapter');
        $parser = new ConfigParser($filesystemHelper);
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

    public function testParseFromFile()
    {
        $filesystemHelper = $this->getMockForAbstractClass('Pozitim\CI\Filesystem\Adapter\AdapterAbstract');
        $parser = new ConfigParser($filesystemHelper);
        $configs = $parser->parseFromFile(realpath(__DIR__) . '/sample.pozitim-ci.yml');
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
