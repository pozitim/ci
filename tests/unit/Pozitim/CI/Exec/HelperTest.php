<?php

namespace Pozitim\CI\Exec;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testExec()
    {
        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $adapter = $this->getMock('Pozitim\CI\Exec\Adapter');

        $expectedResult = new Result();
        $expectedResult->setStrCommand('sh init.sh');
        $expectedResult->setExitCode(-123);

        $adapter->expects($this->once())
            ->method('exec')
            ->with(['sh', 'init.sh'], '/etc')
            ->willReturn($expectedResult);
        $logger->expects($this->once())
            ->method('info')
            ->with($expectedResult->getStrCommand(), ['ExitCode' => $expectedResult->getExitCode()]);

        $helper = new Helper($adapter, $logger);
        $response = $helper->exec(['sh', 'init.sh'], '/etc');
        $this->assertEquals($expectedResult, $response);
    }
}
