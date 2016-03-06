<?php

namespace Pozitim\CI;

use OU\DI;
use Pozitim\CI\Database\BuildEntitySaver;
use Pozitim\CI\Database\Entity\BuildEntity;

class Project
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @var BuildEntity
     */
    protected $buildEntity;

    /**
     * @var string
     */
    protected $projectFile;

    /**
     * @var array
     */
    protected $suiteConfigs;

    /**
     * @var array
     */
    protected $suites;

    /**
     * @return BuildEntity
     */
    public function getBuildEntity()
    {
        if ($this->buildEntity == null) {
            $this->buildEntity = new BuildEntity($this->getDi());
            $this->buildEntity->started_date = date('Y-m-d H:i:s');
            $this->getBuildEntitySaver()->insert($this->buildEntity);
        }
        return $this->buildEntity;
    }

    /**
     * @return array
     */
    public function getSuites()
    {
        if ($this->suites == null) {
            foreach ($this->getSuiteConfigs() as $suiteName => $suiteConfigs) {
                $this->addSuite($suiteName, $suiteConfigs);
            }
        }
        return $this->suites;
    }

    /**
     * @param $suiteName
     * @param array $suiteConfigs
     */
    protected function addSuite($suiteName, array $suiteConfigs)
    {
        $suite = new Suite();
        $suite->setDi($this->getDi());
        $suite->setProject($this);
        $suite->setName($suiteName);
        $suite->setSuiteConfigs($suiteConfigs);
        $this->suites[$suiteName] = $suite;
    }

    /**
     * @return array
     */
    public function getSuiteConfigs()
    {
        if ($this->suiteConfigs == null) {
            $parser = new YamlParser();
            $this->suiteConfigs = $parser->parse($this->getProjectFile());
        }
        return $this->suiteConfigs;
    }

    /**
     * @return string
     */
    public function getProjectFile()
    {
        return $this->projectFile;
    }

    /**
     * @param string $projectFile
     */
    public function setProjectFile($projectFile)
    {
        $this->projectFile = $projectFile;
    }

    /**
     * @return BuildEntitySaver
     * @throws \Exception
     */
    protected function getBuildEntitySaver()
    {
        return $this->getDi()->get('build_entity_saver');
    }

    /**
     * @return DI
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param DI $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }
}
