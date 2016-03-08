<?php

namespace Pozitim\CI\Docker\Compose;

use Monolog\Logger;
use OU\DI;
use Pozitim\CI\Database\Entity\JobEntity;
use Pozitim\CI\Database\JobEntitySaver;
use Pozitim\CI\Notification\NotificationSender;
use Pozitim\CI\Suite;
use Pozitim\Process;
use Zend\Config\Config;

class SuiteRunner
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @var Suite
     */
    protected $currentSuite;

    /**
     * @param Suite $suite
     */
    public function run(Suite $suite)
    {
        $this->currentSuite = $suite;
        try {
            $this->tryRunCommand();
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    protected function tryRunCommand()
    {
        $process = new Process($this->getCommand(), $this->getCurrentSuite()->getTemporaryFolderPath());
        $process->execute(function ($stdout, $stderr) {
            $this->handleProcessOutput($stdout, $stderr);
        });
        $this->handleComplete($process->getExitCode());
        if (empty($this->getCurrentSuite()->getNotificationSettings()) == false) {
            $this->getNotificationSender()->sendJobCompletedNotification($this->getCurrentSuite());
        }
    }

    /**
     * @return array|string
     */
    protected function getCommand()
    {
        $command = array($this->getConfig()->docker_compose_bin, 'run', '--rm', 'default', 'sh', '/init.sh');
        $command = implode(' ', $command);
        return $command;
    }

    /**
     * @param $stdout
     * @param $stderr
     */
    public function handleProcessOutput($stdout, $stderr)
    {
        $buffer = $stdout . $stderr;
        $this->getLogger()->debug(
            'handleProcessOutput',
            array(
                'name' => $this->getCurrentSuite()->getName(),
                'path' => $this->getCurrentSuite()->getTemporaryFolderPath(),
                'buffer' => $buffer
            )
        );
        $this->getJobEntity()->output.= $buffer;
        $this->getJobEntitySaver()->appendOutput($this->getJobEntity(), $buffer);
    }

    /**
     * @param $exitCode
     */
    protected function handleComplete($exitCode)
    {
        $this->getLogger()->debug(
            'handleComplete',
            array(
                'name' => $this->getCurrentSuite()->getName(),
                'path' => $this->getCurrentSuite()->getTemporaryFolderPath(),
                'exitCode' => $exitCode
            )
        );
        $this->getJobEntity()->exit_code = $exitCode;
        $this->getJobEntity()->completed_date = date('Y-m-d H:i:s');
        $this->getJobEntitySaver()->updateExitCode($this->getJobEntity());
        exec('rm -r ' . $this->getCurrentSuite()->getTemporaryFolderPath());
    }

    /**
     * @param $exception
     */
    protected function handleException(\Exception $exception)
    {
        $this->getLogger()->error(
            $exception,
            array(
                'name' => $this->getCurrentSuite()->getName(),
                'path' => $this->getCurrentSuite()->getTemporaryFolderPath()
            )
        );
        $this->getJobEntity()->exit_code = -255;
        $this->getJobEntity()->output.= $exception->getMessage();
        $this->getJobEntitySaver()->updateExitCode($this->getJobEntity(), $exception->getMessage());
    }

    /**
     * @return JobEntity
     */
    protected function getJobEntity()
    {
        return $this->getCurrentSuite()->getJobEntity();
    }

    /**
     * @return Suite
     */
    protected function getCurrentSuite()
    {
        return $this->currentSuite;
    }

    /**
     * @return NotificationSender
     * @throws \Exception
     */
    protected function getNotificationSender()
    {
        return $this->getDi()->get('notification_sender');
    }

    /**
     * @return Logger
     * @throws \Exception
     */
    protected function getLogger()
    {
        return $this->getDi()->get('logger_helper')->getLogger();
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
     * @return Config
     */
    protected function getConfig()
    {
        return $this->getDi()->get('config');
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
