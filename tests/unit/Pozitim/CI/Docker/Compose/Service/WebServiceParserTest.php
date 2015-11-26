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

    public function testDefaultNginxEnv()
    {
        $parser = new WebServiceParser();
        $expected = [
            'NGINX_PUBLIC_FOLDER' => '/project/public',
            'NGINX_INDEX_FILE' => 'index.php'
        ];
        $parsed = $parser->prepareEnvironments();
        $this->assertEquals($expected, $parsed);
    }

    public function testSpecialEnv()
    {
        $parser = new WebServiceParser();
        $expected = [
            'APPLICATION_ENV' => 'pozitim-ci',
            'NGINX_PUBLIC_FOLDER' => '/project/public',
            'NGINX_INDEX_FILE' => 'index.php'
        ];
        $parsed = $parser->prepareEnvironments([
            'env' => ['APPLICATION_ENV' => 'pozitim-ci']
        ]);
        $this->assertEquals($expected, $parsed);
    }

    public function testOverrideNginxEnv()
    {
        $parser = new WebServiceParser();
        $expected = [
            'NGINX_PUBLIC_FOLDER' => '/project/admin',
            'NGINX_INDEX_FILE' => 'api.php'
        ];
        $parsed = $parser->prepareEnvironments([
            'services' => [
                'web' => [
                    'public_folder' => '/project/admin',
                    'index_file' => 'api.php'
                ]
            ]
        ]);
        $this->assertEquals($expected, $parsed);
    }

    public function testPrepareLinks()
    {
        $parser = new WebServiceParser();
        $expected = [
            'gearmand:gearmand',
            'redis:redis'
        ];
        $parsed = $parser->prepareLinks([
            'services' => [
                'gearmand' => null,
                'redis' => null,
                'web' => [
                    'public_folder' => '/project/admin',
                    'index_file' => 'api.php'
                ]
            ]
        ]);
        $this->assertEquals($expected, $parsed);
    }

    public function testPrepareVolumes()
    {
        $parser = new WebServiceParser();
        $expected = ['./source-code:/project'];
        $parsed = $parser->prepareVolumes();
        $this->assertEquals($expected, $parsed);
    }

    public function testPrepareCommand()
    {
        $parser = new WebServiceParser();
        $expected = [
            'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
            'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
            '/usr/sbin/php-fpm -R',
            '/usr/sbin/nginx',
            'sh /project/tests/pozitim-ci/run-tests.sh'
        ];
        $parsed = $parser->prepareCommand(['scripts' => ['sh /project/tests/pozitim-ci/run-tests.sh']]);
        $this->assertEquals($expected, $parsed);
    }
}
