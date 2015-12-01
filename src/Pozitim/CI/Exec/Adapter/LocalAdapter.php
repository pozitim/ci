<?php

namespace Pozitim\CI\Exec\Adapter;

use Pozitim\CI\Exec\Adapter;
use Pozitim\CI\Exec\Result;

class LocalAdapter implements Adapter
{
    /**
     * @param $command
     * @param null $workingDirectory
     * @return Result
     */
    public function exec($command, $workingDirectory = null)
    {
        if (is_array($command)) {
            $command = implode(' ', $command);
        }
        $currentWorkingDir = getcwd();
        if ($workingDirectory !== null) {
            chdir($workingDirectory);
        }
        exec($command, $output, $exitCode);
        if ($workingDirectory !== null) {
            chdir($currentWorkingDir);
        }
        $result = new Result();
        $result->setStrCommand($command);
        $result->setOutput(implode(PHP_EOL, $output));
        $result->setExitCode($exitCode);
        return $result;
    }
}