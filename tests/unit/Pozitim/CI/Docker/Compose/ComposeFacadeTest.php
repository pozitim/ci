<?php

namespace Pozitim\CI\Docker\Compose;

class ComposeFacadeTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultWebService()
    {
        $facade = new ComposeFacade();
        $suiteConfigs = ['services' => ['mysql' => []]];
        $this->assertEquals(['mysql', 'web'], $facade->getServiceNames($suiteConfigs));
        $suiteConfigs = [];
        $this->assertEquals(['web'], $facade->getServiceNames($suiteConfigs));
    }

    public function testGetServiceParser()
    {
        $facade = new ComposeFacade();
        $service = $facade->getServiceParser('web');
        $this->assertInstanceOf('Pozitim\CI\Docker\Compose\Service\WebServiceParser', $service);
    }

    public function testGenerateFileForSuite()
    {
        $facade = new ComposeFacade();
        $suiteConfigs = [
            'image' => 'centos-php54',
            'env' => ['APPLICATION_ENV' => 'pozitim-ci'],
            'services' => ['gearmand' => null, 'memcached' => null],
            'scripts' => ['sh /project/tests/pozitim-ci/install.sh', 'sh /project/tests/pozitim-ci/run.sh']
        ];
        $expected = [
            'web' => [
                'image' => 'pozitim-ci/centos-php54',
                'environment' => [
                    'APPLICATION_ENV' => 'pozitim-ci',
                    'NGINX_PUBLIC_FOLDER' => '/project/public',
                    'NGINX_INDEX_FILE' => 'index.php'
                ],
                'links' => [
                    'gearmand:gearmand',
                    'memcached:memcached'
                ],
                'volumes' => [
                    './source-code:/project'
                ],
                'command' => [
                    'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
                    'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
                    '/usr/sbin/php-fpm -R',
                    '/usr/sbin/nginx',
                    'sh /project/tests/pozitim-ci/install.sh',
                    'sh /project/tests/pozitim-ci/run.sh'
                ]
            ],
            'gearmand' => [
                'image' => 'pataquest/gearmand'
            ],
            'memcached' => [
                'image' => 'memcached'
            ]
        ];
        $composeFile = $facade->generateFileForSuite($suiteConfigs);
        $this->assertEquals($expected, $composeFile);
    }

    public function testGenerateFiles()
    {
        $facade = new ComposeFacade();
        $configs = [
            'suites' => [
                'php54' => [
                    'image' => 'centos-php54',
                    'env' => ['APPLICATION_ENV' => 'pozitim-ci'],
                    'services' => ['gearmand' => null, 'memcached' => null],
                    'scripts' => ['sh /project/tests/pozitim-ci/install.sh', 'sh /project/tests/pozitim-ci/run.sh']
                ],
                'php56' => [
                    'image' => 'centos-php56',
                    'env' => ['APPLICATION_ENV' => 'pozitim-ci'],
                    'services' => ['gearmand' => null, 'memcached' => null],
                    'scripts' => ['sh /project/tests/pozitim-ci/install.sh', 'sh /project/tests/pozitim-ci/run.sh']
                ]
            ]
        ];
        $expected = [
            'php54' => [
                'web' => [
                    'image' => 'pozitim-ci/centos-php54',
                    'environment' => [
                        'APPLICATION_ENV' => 'pozitim-ci',
                        'NGINX_PUBLIC_FOLDER' => '/project/public',
                        'NGINX_INDEX_FILE' => 'index.php'
                    ],
                    'links' => [
                        'gearmand:gearmand',
                        'memcached:memcached'
                    ],
                    'volumes' => [
                        './source-code:/project'
                    ],
                    'command' => [
                        'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
                        'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
                        '/usr/sbin/php-fpm -R',
                        '/usr/sbin/nginx',
                        'sh /project/tests/pozitim-ci/install.sh',
                        'sh /project/tests/pozitim-ci/run.sh'
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
                        'APPLICATION_ENV' => 'pozitim-ci',
                        'NGINX_PUBLIC_FOLDER' => '/project/public',
                        'NGINX_INDEX_FILE' => 'index.php'
                    ],
                    'links' => [
                        'gearmand:gearmand',
                        'memcached:memcached'
                    ],
                    'volumes' => [
                        './source-code:/project'
                    ],
                    'command' => [
                        'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
                        'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
                        '/usr/sbin/php-fpm -R',
                        '/usr/sbin/nginx',
                        'sh /project/tests/pozitim-ci/install.sh',
                        'sh /project/tests/pozitim-ci/run.sh'
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
        $composeFiles = $facade->generateFiles($configs);
        $this->assertEquals($expected, $composeFiles);
    }
}
