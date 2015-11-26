<?php

namespace Pozitim\CI\Docker\Compose\Service;

class MemcachedServiceParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new MemcachedServiceParser();
        $expected = [
            'image' => 'memcached'
        ];
        $this->assertEquals($expected, $parser->parse([]));
    }
}
