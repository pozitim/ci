<?php

namespace Pozitim\CI\Docker\Compose;

class ComposeSettingsGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetServiceParser()
    {
        $di = $this->getMock('OU\DI');
        $generator = new ComposeSettingsGenerator($di);
        $object = $generator->getServiceParser('web');
        $this->assertInstanceOf('Pozitim\CI\Docker\Compose\Service\WebServiceParser', $object);
    }

    public function testGetServiceNames()
    {
        $di = $this->getMock('OU\DI');
        $generator = new ComposeSettingsGenerator($di);
        $suiteConfigs = [
            'services' => [
                'mysql' => null
            ]
        ];
        $serviceNames = $generator->getServiceNames($suiteConfigs);
        $this->assertEquals(['mysql', 'web'], $serviceNames);

        $suiteConfigs = [];
        $serviceNames = $generator->getServiceNames($suiteConfigs);
        $this->assertEquals(['web'], $serviceNames);
    }

    public function testGenerateSettingsForSuite()
    {
        $di = $this->getMock('OU\DI');
        $generator = new ComposeSettingsGenerator($di);
        $suiteConfigs = [
            'image' => 'centos-php56',
            'scripts' => ['php -version'],
            'services' => [
                'gearmand' => null,
                'memcached' => null
            ]
        ];
        $composeSetting = $generator->generateSettingsForSuite($suiteConfigs);
        $expectedComposeSetting = [
            'web' => [
                'image' => 'pozitim-ci/centos-php56',
                'environment' => [
                    'NGINX_PUBLIC_FOLDER' => '/project/public',
                    'NGINX_INDEX_FILE' => 'index.php'
                ],
                'volumes' => [
                    './source-code:/project'
                ],
                'command' => [
                    'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
                    'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
                    '/usr/sbin/php-fpm -R',
                    '/usr/sbin/nginx',
                    'php -version'
                ],
                'links' => [
                    'gearmand:gearmand',
                    'memcached:memcached'
                ]
            ],
            'gearmand' => [
                'image' => 'pataquest/gearmand'
            ],
            'memcached' => [
                'image' => 'memcached'
            ]
        ];
        $this->assertEquals($expectedComposeSetting, $composeSetting);
    }

    public function testGenerateSettings()
    {
        $di = $this->getMock('OU\DI');
        $generator = new ComposeSettingsGenerator($di);
        $projectConfigs = [
            'suites' => [
                'php55' => [
                    'image' => 'centos-php55',
                    'scripts' => ['php -version'],
                    'services' => [
                        'gearmand' => null,
                        'memcached' => null
                    ]
                ],
                'php56' => [
                    'image' => 'centos-php56',
                    'scripts' => ['php -version'],
                    'services' => [
                        'gearmand' => null,
                        'memcached' => null
                    ]
                ]
            ]
        ];
        $composeSettings = $generator->generateSettings($projectConfigs);
        $expectedComposeSettings = [
            'php55' => [
                'web' => [
                    'image' => 'pozitim-ci/centos-php55',
                    'environment' => [
                        'NGINX_PUBLIC_FOLDER' => '/project/public',
                        'NGINX_INDEX_FILE' => 'index.php'
                    ],
                    'volumes' => [
                        './source-code:/project'
                    ],
                    'command' => [
                        'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
                        'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
                        '/usr/sbin/php-fpm -R',
                        '/usr/sbin/nginx',
                        'php -version'
                    ],
                    'links' => [
                        'gearmand:gearmand',
                        'memcached:memcached'
                    ]
                ],
                'gearmand' => [
                    'image' => 'pataquest/gearmand'
                ],
                'memcached' => [
                    'image' => 'memcached'
                ]
            ],
            'php56' => [
                'web' => [
                    'image' => 'pozitim-ci/centos-php56',
                    'environment' => [
                        'NGINX_PUBLIC_FOLDER' => '/project/public',
                        'NGINX_INDEX_FILE' => 'index.php'
                    ],
                    'volumes' => [
                        './source-code:/project'
                    ],
                    'command' => [
                        'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
                        'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
                        '/usr/sbin/php-fpm -R',
                        '/usr/sbin/nginx',
                        'php -version'
                    ],
                    'links' => [
                        'gearmand:gearmand',
                        'memcached:memcached'
                    ]
                ],
                'gearmand' => [
                    'image' => 'pataquest/gearmand'
                ],
                'memcached' => [
                    'image' => 'memcached'
                ]
            ]
        ];
        $this->assertEquals($expectedComposeSettings, $composeSettings);
    }

    public function testGenerateSettingsFromFile()
    {
        $di = $this->getMock('OU\DI');
        $configParser = $this->getMockBuilder('Pozitim\CI\Config\ConfigParser')
            ->disableOriginalConstructor()
            ->getMock();

        $di->expects($this->once())
            ->method('get')
            ->with('config_parser')
            ->willReturn($configParser);
        $configParser->expects($this->once())
            ->method('parseFromFile')
            ->with('/test.yml')
            ->willReturn([
                'suites' => [
                    'php55' => [
                        'image' => 'centos-php55',
                        'scripts' => ['php -version'],
                        'services' => [
                            'gearmand' => null,
                            'memcached' => null
                        ]
                    ],
                    'php56' => [
                        'image' => 'centos-php56',
                        'scripts' => ['php -version'],
                        'services' => [
                            'gearmand' => null,
                            'memcached' => null
                        ]
                    ]
                ]
            ]);
        $generator = new ComposeSettingsGenerator($di);
        $composeSettings = $generator->generateSettingsFromFile('/test.yml');
        $expectedComposeSettings = [
            'php55' => [
                'web' => [
                    'image' => 'pozitim-ci/centos-php55',
                    'environment' => [
                        'NGINX_PUBLIC_FOLDER' => '/project/public',
                        'NGINX_INDEX_FILE' => 'index.php'
                    ],
                    'volumes' => [
                        './source-code:/project'
                    ],
                    'command' => [
                        'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
                        'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
                        '/usr/sbin/php-fpm -R',
                        '/usr/sbin/nginx',
                        'php -version'
                    ],
                    'links' => [
                        'gearmand:gearmand',
                        'memcached:memcached'
                    ]
                ],
                'gearmand' => [
                    'image' => 'pataquest/gearmand'
                ],
                'memcached' => [
                    'image' => 'memcached'
                ]
            ],
            'php56' => [
                'web' => [
                    'image' => 'pozitim-ci/centos-php56',
                    'environment' => [
                        'NGINX_PUBLIC_FOLDER' => '/project/public',
                        'NGINX_INDEX_FILE' => 'index.php'
                    ],
                    'volumes' => [
                        './source-code:/project'
                    ],
                    'command' => [
                        'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
                        'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
                        '/usr/sbin/php-fpm -R',
                        '/usr/sbin/nginx',
                        'php -version'
                    ],
                    'links' => [
                        'gearmand:gearmand',
                        'memcached:memcached'
                    ]
                ],
                'gearmand' => [
                    'image' => 'pataquest/gearmand'
                ],
                'memcached' => [
                    'image' => 'memcached'
                ]
            ]
        ];
        $this->assertEquals($expectedComposeSettings, $composeSettings);
    }
}
