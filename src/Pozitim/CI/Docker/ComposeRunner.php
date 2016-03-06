<?php

namespace Pozitim\CI\Docker;

use Monolog\Logger;
use OU\DI;
use Pozitim\CI\Database\BuildEntitySaver;
use Pozitim\CI\Docker\Compose\SuiteRunner;
use Pozitim\CI\Project;

class ComposeRunner
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @var SuiteRunner
     */
    protected $suiteRunner;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    /**
     * @param $projectFilePath
     * @throws \Exception
     */
    public function run($projectFilePath)
    {
        try {
            $this->tryRun($projectFilePath);
        } catch (\Exception $exception) {
            $this->getLogger()->error($exception);
            throw $exception;
        }
    }

    /**
     * @param $projectFilePath
     */
    protected function tryRun($projectFilePath)
    {
        $project = new Project();
        $project->setDi($this->getDi());
        $project->setProjectFile($projectFilePath);
        foreach ($project->getSuites() as $suiteObject) {
            $this->getSuiteRunner()->run($suiteObject);
        }
        $project->getBuildEntity()->completed_date = date('Y-m-d H:i:s');
        $this->getBuildEntitySaver()->updateCompletedDate($project->getBuildEntity());
    }

    /**
     * @return SuiteRunner
     */
    protected function getSuiteRunner()
    {
        if ($this->suiteRunner == null) {
            $this->suiteRunner = new SuiteRunner();
            $this->suiteRunner->setDi($this->getDi());
        }
        return $this->suiteRunner;
    }

    /**
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->getDi()->get('logger_helper')->getLogger();
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
    protected function getDi()
    {
        return $this->di;
    }
}
