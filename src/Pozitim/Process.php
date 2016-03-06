<?php

namespace Pozitim;

class Process
{
    protected $cmd;
    protected $cwd;
    protected $process;
    protected $pipes;

    /**
     * @var \stdClass
     */
    protected $status;

    /**
     * @param $cmd
     * @param null $cwd
     */
    public function __construct($cmd, $cwd = null)
    {
        $this->cmd = $cmd;
        $this->cwd = $cwd;
    }

    /**
     * @param \Closure|null $callback
     */
    public function execute(\Closure $callback = null)
    {
        while ($this->refreshStatus()) {
            // TODO : timeout
            if (is_callable($callback)) {
                $stdout = stream_get_contents($this->pipes[1]);
                $stderr = stream_get_contents($this->pipes[2]);
                if ($stdout != '' || $stderr != '') {
                    $callback($stdout, $stderr);
                }
            }
            usleep(5000);
        }
        $this->cleanup();
    }

    /**
     * @return mixed
     */
    protected function refreshStatus()
    {
        $this->status = (object)proc_get_status($this->getProcess());
        return $this->status->running;
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->status->exitcode;
    }

    protected function cleanup()
    {
        fclose($this->pipes[1]);
        fclose($this->pipes[2]);
        proc_close($this->getProcess());
        unset($this->process);
        unset($this->pipes);
    }

    /**
     * @return resource
     */
    protected function getProcess()
    {
        if ($this->process == null) {
            $spec = array(
                1 => array('pipe', 'w'),
                2 => array('pipe', 'w')
            );
            $this->process = proc_open($this->cmd, $spec, $this->pipes, $this->cwd);
            stream_set_blocking($this->pipes[1], 0);
            stream_set_blocking($this->pipes[2], 0);
        }
        return $this->process;
    }
}
