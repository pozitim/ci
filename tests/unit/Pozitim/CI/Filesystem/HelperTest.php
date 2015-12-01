<?php

namespace Pozitim\CI\Filesystem;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDirectory()
    {
        $adapter = $this->getMock('Pozitim\CI\Filesystem\Adapter');

        $adapter->expects($this->once())
            ->method('createDirectory')
            ->with('/directory');

        $helper = new Helper($adapter);
        $helper->createDirectory('/directory');
    }

    public function testRemovePath()
    {
        $adapter = $this->getMock('Pozitim\CI\Filesystem\Adapter');

        $adapter->expects($this->once())
            ->method('removePath')
            ->with('/directory');

        $helper = new Helper($adapter);
        $helper->removePath('/directory');
    }

    public function testCopy()
    {
        $adapter = $this->getMock('Pozitim\CI\Filesystem\Adapter');

        $adapter->expects($this->once())
            ->method('copy')
            ->with('/target', '/destination');

        $helper = new Helper($adapter);
        $helper->copy('/target', '/destination');
    }

    public function testSetFileContent()
    {
        $adapter = $this->getMock('Pozitim\CI\Filesystem\Adapter');

        $adapter->expects($this->once())
            ->method('setFileContent')
            ->with('/target', 'content', true);

        $helper = new Helper($adapter);
        $helper->setFileContent('/target', 'content', true);
    }

    public function testGetFileContent()
    {
        $adapter = $this->getMock('Pozitim\CI\Filesystem\Adapter');

        $adapter->expects($this->once())
            ->method('getFileContent')
            ->with('/target')
            ->willReturn('content');

        $helper = new Helper($adapter);
        $response = $helper->getFileContent('/target');
        $this->assertEquals('content', $response);
    }

    public function testSetYamlFileContent()
    {
        $adapter = $this->getMock('Pozitim\CI\Filesystem\Adapter');

        $adapter->expects($this->once())
            ->method('setYamlFileContent')
            ->with('/target', ['content']);

        $helper = new Helper($adapter);
        $helper->setYamlFileContent('/target', ['content']);
    }

    public function testGetYamlFileContent()
    {
        $adapter = $this->getMock('Pozitim\CI\Filesystem\Adapter');

        $adapter->expects($this->once())
            ->method('getYamlFileContent')
            ->with('/target')
            ->willReturn(['content']);

        $helper = new Helper($adapter);
        $response = $helper->getYamlFileContent('/target');
        $this->assertEquals(['content'], $response);
    }
}
