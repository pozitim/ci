<?php

namespace Pozitim\CI\Exec\Adapter;

class LocalAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testExec()
    {
        $oldCwd = getcwd();
        $adapter = new LocalAdapter();
        $result = $adapter->exec(['sh', 'test.sh'], realpath(__DIR__));
        $this->assertEquals(0, $result->getExitCode());
        $this->assertEquals('OK', $result->getOutput());
        $this->assertEquals($oldCwd, getcwd());

        $result = $adapter->exec('sh test.sh', realpath(__DIR__));
        $this->assertEquals('sh test.sh', $result->getStrCommand());
        $this->assertEquals(0, $result->getExitCode());
        $this->assertEquals('OK', $result->getOutput());
        $this->assertEquals($oldCwd, getcwd());
    }
}
