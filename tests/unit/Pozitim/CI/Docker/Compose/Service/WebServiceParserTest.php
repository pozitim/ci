<?php

namespace Pozitim\CI\Docker\Compose\Service;

class WebServiceParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new WebServiceParser();
        $expected = [
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
        ];
        $suiteConfigs = [
            'image' => 'centos-php54',
            'env' => ['APPLICATION_ENV' => 'pozitim-ci'],
            'services' => ['gearmand' => null, 'memcached' => null],
            'scripts' => ['sh /project/tests/pozitim-ci/install.sh', 'sh /project/tests/pozitim-ci/run.sh']
        ];
        $parsed = $parser->parse($suiteConfigs);
        $this->assertEquals($expected, $parsed);
    }

    public function testEmptyLinks()
    {
        $parser = new WebServiceParser();
        $expected = [
            'image' => 'pozitim-ci/centos-php54',
            'environment' => [
                'APPLICATION_ENV' => 'pozitim-ci',
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
                'sh /project/tests/pozitim-ci/install.sh',
                'sh /project/tests/pozitim-ci/run.sh'
            ]
        ];
        $suiteConfigs = [
            'image' => 'centos-php54',
            'env' => ['APPLICATION_ENV' => 'pozitim-ci'],
            'scripts' => ['sh /project/tests/pozitim-ci/install.sh', 'sh /project/tests/pozitim-ci/run.sh']
        ];
        $parsed = $parser->parse($suiteConfigs);
        $this->assertEquals($expected, $parsed);
    }

    public function testDefaultNginxEnv()
    {
        $parser = new WebServiceParser();
        $expected = [
            'environment' => [
                'NGINX_PUBLIC_FOLDER' => '/project/public',
                'NGINX_INDEX_FILE' => 'index.php'
            ]
        ];
        $serviceConfigs = $parser->prepareEnvironments([], []);
        $this->assertEquals($expected, $serviceConfigs);
    }

    public function testSpecialEnv()
    {
        $parser = new WebServiceParser();
        $expected = [
            'environment' => [
                'APPLICATION_ENV' => 'pozitim-ci',
                'NGINX_PUBLIC_FOLDER' => '/project/public',
                'NGINX_INDEX_FILE' => 'index.php'
            ]
        ];
        $suiteConfigs = [
            'env' => ['APPLICATION_ENV' => 'pozitim-ci']
        ];
        $serviceConfigs = $parser->prepareEnvironments([], $suiteConfigs);
        $this->assertEquals($expected, $serviceConfigs);
    }

    public function testOverrideNginxEnv()
    {
        $parser = new WebServiceParser();
        $expected = [
            'environment' => [
                'NGINX_PUBLIC_FOLDER' => '/project/admin',
                'NGINX_INDEX_FILE' => 'api.php'
            ]
        ];
        $suiteConfigs = [
            'services' => [
                'web' => [
                    'public_folder' => '/project/admin',
                    'index_file' => 'api.php'
                ]
            ]
        ];
        $serviceConfigs = $parser->prepareEnvironments([], $suiteConfigs);
        $this->assertEquals($expected, $serviceConfigs);
    }

    public function testPrepareLinks()
    {
        $parser = new WebServiceParser();
        $expected = [
            'links' => [
                'gearmand:gearmand',
                'redis:redis'
            ]
        ];
        $suiteConfigs = [
            'services' => [
                'gearmand' => null,
                'redis' => null,
                'web' => [
                    'public_folder' => '/project/admin',
                    'index_file' => 'api.php'
                ]
            ]
        ];
        $serviceConfig = $parser->prepareLinks([], $suiteConfigs);
        $this->assertEquals($expected, $serviceConfig);
    }

    public function testPrepareVolumes()
    {
        $parser = new WebServiceParser();
        $expected = ['volumes' => ['./source-code:/project']];
        $serviceConfigs = $parser->prepareVolumes([], []);
        $this->assertEquals($expected, $serviceConfigs);
    }

    public function testPrepareCommand()
    {
        $parser = new WebServiceParser();
        $expected = [
            'command' => [
                'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
                'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
                '/usr/sbin/php-fpm -R',
                '/usr/sbin/nginx',
                'sh /project/tests/pozitim-ci/run-tests.sh'
            ]
        ];
        $suiteConfigs = ['scripts' => ['sh /project/tests/pozitim-ci/run-tests.sh']];
        $parsed = $parser->prepareCommand([], $suiteConfigs);
        $this->assertEquals($expected, $parsed);
    }
}
