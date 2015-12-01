<?php

namespace Pozitim\CI\Filesystem\Adapter;

class ExecAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDirectory()
    {
        $execHelper = $this->getMock('Pozitim\CI\Exec\Adapter');
        $execHelper->expects($this->once())
            ->method('exec')
            ->with(['mkdir', '-p', '/directory']);

        $adapter = new ExecAdapter($execHelper);
        $adapter->createDirectory('/directory');
    }

    public function testRemovePath()
    {
        $execHelper = $this->getMock('Pozitim\CI\Exec\Adapter');
        $execHelper->expects($this->once())
            ->method('exec')
            ->with(['rm', '-rf', '/directory']);

        $adapter = new ExecAdapter($execHelper);
        $adapter->removePath('/directory');
    }

    public function testCopy()
    {
        $execHelper = $this->getMock('Pozitim\CI\Exec\Adapter');
        $execHelper->expects($this->once())
            ->method('exec')
            ->with(['cp', '-r', '/target', '/destination']);

        $adapter = new ExecAdapter($execHelper);
        $adapter->copy('/target', '/destination');
    }
}
