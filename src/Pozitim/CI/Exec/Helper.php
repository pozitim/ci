<?php

namespace Pozitim\CI\Exec;

use Psr\Log\LoggerInterface;

class Helper implements Adapter
{
    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Adapter $adapter
     * @param LoggerInterface $logger
     */
    public function __construct(Adapter $adapter, LoggerInterface $logger)
    {
        $this->adapter = $adapter;
        $this->logger = $logger;
    }

    /**
     * @param $command
     * @param null $workingDirectory
     * @return Result
     */
    public function exec($command, $workingDirectory = null)
    {
        $result = $this->adapter->exec($command, $workingDirectory);
        $this->logger->info($result->getStrCommand(), ['ExitCode' => $result->getExitCode()]);
        return $result;
    }
}
