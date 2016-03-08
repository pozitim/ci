<?php

namespace Pozitim\CI\Docker\Compose;

use Pozitim\CI\Docker\Compose\Service\Service;
use Pozitim\CI\Suite;
use Symfony\Component\Yaml\Yaml;

class TemporaryFolderSetupHelper
{
    /**
     * @var string
     */
    protected $baseTempPath;

    /**
     * @var string
     */
    protected $temporaryFolder;

    /**
     * @param $baseTempPath
     */
    public function __construct($baseTempPath)
    {
        $this->baseTempPath = $baseTempPath;
    }

    /**
     * @param Suite $suite
     * @return string
     */
    public function setUp(Suite $suite)
    {
        $this->createTemporaryFolder();
        $this->generateDockerComposeFiles($suite);
        $this->copySourceCodeFolderToTemporaryFolder($suite);
        return $this->getTemporaryFolder();
    }

    protected function createTemporaryFolder()
    {
        $this->temporaryFolder = realpath($this->baseTempPath) . '/' . uniqid('suite-');
        mkdir($this->temporaryFolder);
    }

    /**
     * @param Suite $suite
     * @return array
     */
    protected function generateDockerComposeFiles(Suite $suite)
    {
        /**
         * @var Service $service
         */
        $dockerComposeFileContent = array();
        foreach ($suite->getServices() as $serviceName => $service) {
            $dockerComposeFileContent[$serviceName] = $service->getDockerComposeContent();
        }
        $this->saveInitShFile($dockerComposeFileContent);
        $this->saveDockerComposeFile($dockerComposeFileContent);
    }

    /**
     * @param array $dockerComposeFileContent
     */
    protected function saveInitShFile(array $dockerComposeFileContent)
    {
        $content = "#!/bin/bash" . PHP_EOL
            . implode(PHP_EOL, $dockerComposeFileContent['default']['command']);
        file_put_contents($this->getTemporaryFolder() . '/init.sh', $content);
    }

    /**
     * @param array $dockerComposeFileContent
     */
    protected function saveDockerComposeFile(array $dockerComposeFileContent)
    {
        $dockerComposeFileContent['default']['command'] = ['sh /init.sh'];
        $content = Yaml::dump($dockerComposeFileContent);
        file_put_contents($this->getTemporaryFolder() . '/docker-compose.yml', $content);
    }

    /**
     * @param Suite $suite
     */
    protected function copySourceCodeFolderToTemporaryFolder(Suite $suite)
    {
        $sourceCodeFolder = dirname($suite->getProject()->getProjectFile());
        exec('cp -r ' . $sourceCodeFolder . ' ' . $this->getTemporaryFolder() . '/source-code');
    }

    /**
     * @return string
     */
    public function getTemporaryFolder()
    {
        return $this->temporaryFolder;
    }
}
