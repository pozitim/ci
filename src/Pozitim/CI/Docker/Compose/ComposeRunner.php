<?php

namespace Pozitim\CI\Docker\Compose;

use OU\DI;
use Pozitim\CI\Exec\Result;
use Pozitim\CI\Filesystem\Helper;
use Zend\Config\Config;

class ComposeRunner
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    /**
     * @param $projectFile
     * @return array
     * @throws \Exception
     */
    public function run($projectFile)
    {
        /**
         * @var ComposeSettingsGenerator $composeSettingsGenerator
         */
        $composeSettingsGenerator = $this->di->get('docker_compose_settings_generator');
        $composeSettingsList = $composeSettingsGenerator->generateSettingsFromFile($projectFile);
        $results = [];
        foreach ($composeSettingsList as $suiteName => $composeSettings) {
            $results[$suiteName] = $this->runForSuite($projectFile, $composeSettings);
        }
        return $results;
    }

    /**
     * @param $projectFile
     * @param array $composeSettings
     * @return Result
     */
    public function runForSuite($projectFile, array $composeSettings)
    {
        $projectFolder = dirname($projectFile);
        $temporaryFolder = $this->prepareTemporaryFolder($projectFolder);
        $this->prepareComposeFile($temporaryFolder, $composeSettings);
        return $this->runDockerCompose($temporaryFolder);
    }

    /**
     * @param $projectFolder
     * @return string
     */
    public function prepareTemporaryFolder($projectFolder)
    {
        /**
         * @var Helper $filesystemHelper
         * @var Config $config
         */
        $filesystemHelper = $this->di->get('filesystem_helper');
        $config = $this->di->get('config');
        $temporaryFolder = realpath($config->tmp_path) . '/' . uniqid('suite-');
        $filesystemHelper->createDirectory($temporaryFolder);
        $filesystemHelper->copy($projectFolder, $temporaryFolder . '/source-code');
        return $temporaryFolder;
    }

    /**
     * @param $temporaryFolder
     * @param array $composeSettings
     */
    public function prepareComposeFile($temporaryFolder, array $composeSettings)
    {
        /**
         * @var Helper $filesystemHelper
         */
        $filesystemHelper = $this->di->get('filesystem_helper');
        $temporaryFolder = realpath($temporaryFolder);
        $volumes = isset($composeSettings['web']['volumes']) ? $composeSettings['web']['volumes'] : [];
        $volumes[] = './init.sh:/init.sh';
        $composeSettings['web']['volumes'] = $volumes;
        $initFile = "#!/bin/bash" . PHP_EOL . implode(PHP_EOL, $composeSettings['web']['command']);
        $composeSettings['web']['command'] = ['sh /init.sh'];
        $filesystemHelper->setFileContent($temporaryFolder . '/init.sh', $initFile);
        $filesystemHelper->setYamlFileContent($temporaryFolder . '/docker-compose.yml', $composeSettings);
    }

    /**
     * @param $temporaryFolder
     * @return Result
     */
    public function runDockerCompose($temporaryFolder)
    {
        /**
         * @var Helper $filesystemHelper
         * @var \Pozitim\CI\Exec\Helper $execHelper
         * @var Config $config
         */
        $filesystemHelper = $this->di->get('filesystem_helper');
        $execHelper = $this->di->get('exec_helper');
        $config = $this->di->get('config');
        $temporaryFolder = realpath($temporaryFolder);
        $command = [$config->docker_compose_bin, 'run', '--rm', 'web', 'sh', '/init.sh'];
        $result = $execHelper->exec($command, $temporaryFolder);
        $filesystemHelper->removePath($temporaryFolder);
        return $result;
    }
}
