<?php

namespace Pozitim\CI\Exec;

class Result
{
    protected $strCommand;
    protected $output;
    protected $exitCode= -255;

    /**
     * @return mixed
     */
    public function getStrCommand()
    {
        return $this->strCommand;
    }

    /**
     * @param mixed $strCommand
     */
    public function setStrCommand($strCommand)
    {
        $this->strCommand = $strCommand;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param mixed $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * @param int $exitCode
     */
    public function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;
    }
}
