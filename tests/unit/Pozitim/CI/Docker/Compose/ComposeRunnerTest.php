<?php

namespace Pozitim\CI\Docker\Compose\Runner;

use Pozitim\CI\Docker\Compose\ComposeRunner;
use Pozitim\CI\Exec\Result;

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $projectFile = '/project-folder/.pozitim-ci.yml';
        $php55ComposeSetting = [
            'web' => [
                'command' => ['sh /init.sh']
            ]
        ];
        $php56ComposeSetting = [
            'web' => [
                'command' => ['sh /init.sh']
            ]
        ];
        $composeSettingList = [];
        $composeSettingList[] = $php55ComposeSetting;
        $composeSettingList[] = $php56ComposeSetting;
        $resultForPhp55 = new Result();
        $resultForPhp56 = new Result();

        $di = $this->getMock('OU\DI');
        $composeSettingsGenerator = $this->getMockBuilder('Pozitim\CI\Docker\Compose\ComposeSettingsGenerator')
            ->disableOriginalConstructor()
            ->getMock();
        $composeSettingsGenerator->expects($this->once())
            ->method('generateSettingsFromFile')
            ->with($projectFile)
            ->willReturn($composeSettingList);
        $di->expects($this->once())
            ->method('get')
            ->with('docker_compose_settings_generator')
            ->willReturn($composeSettingsGenerator);
        $composeRunner = $this->getMockBuilder('Pozitim\CI\Docker\Compose\ComposeRunner')
            ->setConstructorArgs([$di])
            ->setMethods(['runForSuite'])
            ->getMock();
        $composeRunner->expects($this->at(0))
            ->method('runForSuite')
            ->with($projectFile, $php55ComposeSetting)
            ->willReturn($resultForPhp55);
        $composeRunner->expects($this->at(1))
            ->method('runForSuite')
            ->with($projectFile, $php56ComposeSetting)
            ->willReturn($resultForPhp56);

        /**
         * @var ComposeRunner $composeRunner
         */
        $responses = $composeRunner->run($projectFile);
        $expectedResults = [$resultForPhp55, $resultForPhp56];
        $this->assertEquals($expectedResults, $responses);
    }

    public function testRunForSuite()
    {
        $projectFile = '/project-folder/.pozitim-ci.yml';
        $projectFolder = '/project-folder';
        $temporaryFolder = '/project-temporary-folder';
        $result = new Result();
        $composeSetting = [
            'web' => [
                'command' => ['sh /init.sh']
            ]
        ];

        $composeRunner = $this->getMockBuilder('Pozitim\CI\Docker\Compose\ComposeRunner')
            ->setMethods(['prepareTemporaryFolder', 'prepareComposeFile', 'runDockerCompose'])
            ->disableOriginalConstructor()
            ->getMock();
        $composeRunner->expects($this->once())
            ->method('prepareTemporaryFolder')
            ->with($projectFolder)
            ->willReturn($temporaryFolder);
        $composeRunner->expects($this->once())
            ->method('prepareComposeFile')
            ->with($temporaryFolder, $composeSetting);
        $composeRunner->expects($this->once())
            ->method('runDockerCompose')
            ->with($temporaryFolder)
            ->willReturn($result);

        /**
         * @var ComposeRunner $composeRunner
         */
        $response = $composeRunner->runForSuite($projectFile, $composeSetting);
        $this->assertEquals($result , $response);
    }

    public function testPrepareTemporaryFolder()
    {
        $di = $this->getMock('OU\DI');
        $filesystemHelper = $this->getMock('Pozitim\CI\Filesystem\Adapter');
        $projectFolder = '/project';
        $tmpPath = realpath(__DIR__);

        $di->expects($this->at(0))
            ->method('get')
            ->with('filesystem_helper')
            ->willReturn($filesystemHelper);
        $di->expects($this->at(1))
            ->method('get')
            ->with('config')
            ->willReturn((object)['tmp_path' => $tmpPath]);
        $filesystemHelper->expects($this->once())
            ->method('createDirectory')
            ->with($this->stringContains('suite-'));
        $filesystemHelper->expects($this->once())
            ->method('copy')
            ->with($projectFolder, $this->stringEndsWith('/source-code'));

        $runner = new ComposeRunner($di);
        $temporaryFolder = $runner->prepareTemporaryFolder($projectFolder);
        $this->assertStringStartsWith($tmpPath, $temporaryFolder);
        $this->assertContains('/suite-', $temporaryFolder);
    }

    public function testPrepareComposeFile()
    {
        $di = $this->getMock('OU\DI');
        $filesystemHelper = $this->getMock('Pozitim\CI\Filesystem\Adapter');
        $temporaryFolder = realpath(__DIR__);
        $initFileContent = file_get_contents(realpath(__DIR__) . '/init.sh.1');
        $composeSetting = [
            'web' => [
                'command' => ['php --version']
            ]
        ];
        $expectedDockerFile = [
            'web' => [
                'volumes' => ['./init.sh:/init.sh'],
                'command' => ['sh /init.sh']
            ]
        ];

        $di->expects($this->once())
            ->method('get')
            ->with('filesystem_helper')
            ->willReturn($filesystemHelper);
        $filesystemHelper->expects($this->once())
            ->method('setFileContent')
            ->with($temporaryFolder . '/init.sh', $initFileContent);
        $filesystemHelper->expects($this->once())
            ->method('setYamlFileContent')
            ->with($temporaryFolder . '/docker-compose.yml', $expectedDockerFile);

        $runner = new ComposeRunner($di);
        $runner->prepareComposeFile($temporaryFolder, $composeSetting);
    }

    public function testRunDockerCompose()
    {
        $di = $this->getMock('OU\DI');
        $filesystemHelper = $this->getMock('Pozitim\CI\Filesystem\Adapter');
        $execHelper = $this->getMock('Pozitim\CI\Exec\Adapter');
        $temporaryFolder = realpath(__DIR__);
        $result = new Result();

        $di->expects($this->at(0))
            ->method('get')
            ->with('filesystem_helper')
            ->willReturn($filesystemHelper);
        $di->expects($this->at(1))
            ->method('get')
            ->with('exec_helper')
            ->willReturn($execHelper);
        $di->expects($this->at(2))
            ->method('get')
            ->with('config')
            ->willReturn((object)['docker_compose_bin' => '/usr/bin/docker_compose']);
        $filesystemHelper->expects($this->once())
            ->method('removePath')
            ->with($temporaryFolder);
        $execHelper->expects($this->once())
            ->method('exec')
            ->with(['/usr/bin/docker_compose', 'run', '--rm', 'web', 'sh', '/init.sh'], $temporaryFolder)
            ->willReturn($result);

        $runner = new ComposeRunner($di);
        $response = $runner->runDockerCompose($temporaryFolder);
        $this->assertEquals($result, $response);
    }
}
