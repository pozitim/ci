<?php

namespace Pozitim\CI\Docker\Compose\Service;

class GearmandServiceParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new GearmandServiceParser();
        $expected = [
            'image' => 'pataquest/gearmand'
        ];
        $this->assertEquals($expected, $parser->parse([]));
    }
}
