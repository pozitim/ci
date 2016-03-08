<?php

namespace Pozitim\CI;

use OU\DI;
use Pozitim\CI\Database\Entity\JobEntity;
use Pozitim\CI\Database\JobEntitySaver;
use Pozitim\CI\Docker\Compose\ServiceBuilder;
use Pozitim\CI\Docker\Compose\TemporaryFolderSetupHelper;

class Suite
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var JobEntity
     */
    protected $jobEntity;

    /**
     * @var string
     */
    protected $temporaryFolderPath;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var array
     */
    protected $environments = array();

    /**
     * @var array
     */
    protected $commands = array();

    /**
     * @var array
     */
    protected $services = array();

    /**
     * @var array
     */
    protected $notificationSettings = array();

    /**
     * @param array $extendedConfig
     */
    public function setSuiteConfigs(array $extendedConfig)
    {
        if (isset($extendedConfig['image'])) {
            $this->setImage($extendedConfig['image']);
        }
        if (isset($extendedConfig['environments'])) {
            $this->setEnvironments($extendedConfig['environments']);
        }
        if (isset($extendedConfig['commands'])) {
            $this->setCommands($extendedConfig['commands']);
        }
        if (isset($extendedConfig['notifications'])) {
            $this->setNotificationSettings($extendedConfig['notifications']);
        }
        $this->setServices($extendedConfig['services']);
        $this->setTemporaryFolder();
    }

    /**
     * @param array $services
     */
    protected function setServices(array $services)
    {
        foreach ($services as $serviceName => $serviceConfigs) {
            $this->services[$serviceName] = ServiceBuilder::build($this, $serviceName, $serviceConfigs);
        }
    }

    protected function setTemporaryFolder()
    {
        $this->temporaryFolderPath = $this->getTemporarySetupHelper()->setUp($this);
    }

    /**
     * @return JobEntity
     */
    public function getJobEntity()
    {
        if ($this->jobEntity == null) {
            $this->jobEntity = new JobEntity($this->getDi());
            $this->jobEntity->name = $this->getName();
            $this->jobEntity->started_date = date('Y-m-d H:i:s');
            $this->jobEntity->setBuildEntity($this->getProject()->getBuildEntity());
            $this->getJobEntitySaver()->insert($this->jobEntity);
        }
        return $this->jobEntity;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return array
     */
    public function getEnvironments()
    {
        return $this->environments;
    }

    /**
     * @param array $environments
     */
    public function setEnvironments(array $environments)
    {
        $this->environments = $environments;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param array $commands
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    /**
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return array
     */
    public function getServiceNames()
    {
        return array_keys($this->services);
    }

    /**
     * @return array
     */
    public function getNotificationSettings()
    {
        return $this->notificationSettings;
    }

    /**
     * @param array $notifications
     */
    public function setNotificationSettings(array $notificationSettings)
    {
        $this->notificationSettings = $notificationSettings;
    }

    /**
     * @return string
     */
    public function getTemporaryFolderPath()
    {
        return $this->temporaryFolderPath;
    }
    /**
     * @return TemporaryFolderSetupHelper
     */
    protected function getTemporarySetupHelper()
    {
        return $this->getDi()->get('temporary_folder_setup_helper');
    }

    /**
     * @return JobEntitySaver
     * @throws \Exception
     */
    protected function getJobEntitySaver()
    {
        return $this->getDi()->get('job_entity_saver');
    }

    /**
     * @return DI
     */
    protected function getDi()
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
