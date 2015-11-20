<?php

namespace Pozitim\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use OU\DI;
use Zend\Config\Config;

class MonologHelper
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @var array
     */
    protected $loggers = [];

    /**
     * @var array
     */
    protected $defaultContext = [];

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    /**
     * @param string $name
     * @return Logger
     */
    public function getLogger($name = '')
    {
        $name = $name == '' ? $this->getConfig()->logger->default_name : trim($name);
        if (!isset($this->loggers[$name])) {
            $this->loggers[$name] = $this->createLogger($name);
        }
        return $this->loggers[$name];
    }

    public function resetLoggers()
    {
        foreach (array_values($this->loggers) as $logger) {
            $this->resetLogger($logger);
        }
    }

    public function reCreateLoggers()
    {
        foreach ($this->loggers as $name => $logger) {
            unset($logger);
            $this->loggers[$name] = $this->createLogger($name);
        }
    }

    /**
     * @param Logger $logger
     */
    public function resetLogger(Logger $logger)
    {
        if (count($logger->getProcessors()) > 0) {
            $logger->popProcessor();
        }
        $logger->pushProcessor(function ($record) {
            $record['context']['ReqID'] = $this->getConfig()->req_id;
            return $record;
        });
    }

    /**
     * @param $name
     * @return Logger
     * @throws \Exception
     */
    public function createLogger($name)
    {
        $logger = new Logger($name);
        $stream = new StreamHandler($this->getFilePath($name), $this->getLogLevel($name));
        $stream->setFormatter(new LineFormatter("[%datetime%] [%level_name%] %message% %context%\n"));
        $logger->pushHandler($stream);
        $this->resetLogger($logger);
        return $logger;
    }

    /**
     * @param $name
     * @return string
     */
    protected function getLogLevel($name)
    {
        $level = $this->getConfig()->logger->default_level;
        if (isset($this->getConfig()->logger->get($name)->level)) {
            $level = $this->getConfig()->logger->get($name)->level;
        }
        return $level;
    }

    /**
     * @param $name
     * @return string
     */
    protected function getFilePath($name)
    {
        $basePath = $this->getConfig()->logger->default_path;
        if (isset($this->getConfig()->logger->get($name)->path)) {
            $basePath = $this->getConfig()->logger->get($name)->path;
        }
        return $basePath . '/' . $name . '-' . $this->getConfig()->environment . '-' . date('Y-m-d') . '.log';
    }

    /**
     * @return Config
     * @throws \Exception
     */
    public function getConfig()
    {
        return $this->di->get('config');
    }
}
