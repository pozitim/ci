<?php

namespace Pozitim\CI\Docker\Compose\Service;

class RedisServiceParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new RedisServiceParser();
        $expected = [
            'image' => 'redis'
        ];
        $this->assertEquals($expected, $parser->parse([]));
    }
}
