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
                'default' => [
                    'os' => 'centos',
                    'env' => ['APPLICATION_ENV' => 'pozitim-ci'],
                    'services' => [
                        'mysql' => [
                            'database' => 'charging',
                            'username' => 'root',
                            'password' => '',
                            'charset' => 'utf8'
                        ],
                        'nginx' => [
                            'public' => '/project/public'
                        ],
                        'memcached' => null,
                        'gearmand' => null,
                        'php-fpm' => null
                    ],
                    'php-extensions' => ['php-opcache', 'php-xml'],
                    'php-versions' => ['5.4'],
                    'scripts' => ['sh /project/tests/pozitim-ci-files/install.sh']
                ],
                'phalcon2' => [
                    'os' => 'centos',
                    'env' => ['APPLICATION_ENV' => 'pozitim-ci'],
                    'services' => [
                        'mysql' => [
                            'database' => 'charging',
                            'username' => 'root',
                            'password' => '',
                            'charset' => 'utf8'
                        ],
                        'nginx' => [
                            'public' => '/project/public'
                        ],
                        'memcached' => null,
                        'gearmand' => null,
                        'php-fpm' => null
                    ],
                    'php-extensions' => ['php-opcache', 'php-xml'],
                    'php-versions' => ['5.5', '5.6'],
                    'scripts' => ['sh /project/tests/pozitim-ci-files/install2.sh']
                ]
            ]
        ];
        $this->assertEquals($expected, $configs);
    }
}
