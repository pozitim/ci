<?php

namespace Pozitim\CI\Docker\Compose\Service;

class MongoServiceParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new MongoServiceParser();
        $expected = [
            'image' => 'mongo'
        ];
        $this->assertEquals($expected, $parser->parse([]));
    }
}
