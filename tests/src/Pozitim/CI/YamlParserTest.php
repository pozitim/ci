<?php

namespace Pozitim\CI;

class YamlParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $projectFile = realpath(__DIR__ . '/fixtures/') . '/yaml_parser_test_file.yml';
        $parser = new YamlParser();
        $parsedConfigs = $parser->parse($projectFile);
        $expected = array(
            'suite1' => array(
                'image' => 'pozitim-ci/centos-php54',
                'environments' => array(
                    'APPLICATION_ENV' => 'pozitim-ci'
                ),
                'commands' => array(
                    'echo 1'
                )
            ),
            'suite2' => array(
                'image' => 'pozitim-ci/centos-php55',
                'environments' => array(
                    'APPLICATION_ENV' => 'pozitim-ci'
                ),
                'commands' => array(
                    'echo 2'
                )
            ),
            'suite3' => array(
                'image' => 'pozitim-ci/centos-php56',
                'environments' => array(
                    'APPLICATION_ENV' => 'pozitim-ci'
                ),
                'commands' => array(
                    'echo 3'
                )
            )
        );
        $this->assertEquals($expected, $parsedConfigs);
    }
}
