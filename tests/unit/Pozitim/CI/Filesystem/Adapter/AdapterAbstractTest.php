<?php

namespace Pozitim\CI\Filesystem\Adapter;

use Pozitim\DiSingleton;

class AdapterAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testSetYamlFileContent()
    {
        $sysConfig = DiSingleton::getInstance()->getDi()->get('config');
        $execHelper = $this->getMock('Pozitim\CI\Exec\Adapter');

        $target = $sysConfig->tmp_path . '/test.txt';
        $content = ['a' => 1];

        $adapter = new ExecAdapter($execHelper);
        $adapter->setYamlFileContent($target, $content);

        $this->assertEquals($content, $adapter->getYamlFileContent($target));
        unlink($target);
    }

    public function testSetFileContent()
    {
        $sysConfig = DiSingleton::getInstance()->getDi()->get('config');
        $execHelper = $this->getMock('Pozitim\CI\Exec\Adapter');

        $target = $sysConfig->tmp_path . '/test.txt';
        $content = 'a';

        $adapter = new ExecAdapter($execHelper);
        $adapter->setFileContent($target, $content, true);

        $this->assertEquals($content, $adapter->getFileContent($target));
        unlink($target);
    }
}
