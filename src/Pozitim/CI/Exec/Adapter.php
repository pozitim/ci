<?php

namespace Pozitim\CI\Exec;

interface Adapter
{
    /**
     * @param $command
     * @param null $workingDirectory
     * @return Result
     */
    public function exec($command, $workingDirectory = null);
}
