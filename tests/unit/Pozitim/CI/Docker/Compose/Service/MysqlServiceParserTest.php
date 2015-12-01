<?php

namespace Pozitim\CI\Docker\Compose\Service;

class MysqlServiceParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new MysqlServiceParser();
        $expected = [
            'image' => 'mysql',
            'environment' => [
                'MYSQL_DATABASE' => 'project',
                'MYSQL_ALLOW_EMPTY_PASSWORD' => 'yes'
            ]
        ];
        $this->assertEquals($expected, $parser->parse(['services' => ['mysql' => ['database' => 'project']]]));
    }
}
