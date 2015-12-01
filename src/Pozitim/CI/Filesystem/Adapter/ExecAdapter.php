<?php

namespace Pozitim\CI\Filesystem\Adapter;

use Pozitim\CI\Filesystem\Adapter;

class ExecAdapter extends AdapterAbstract implements Adapter
{
    /**
     * @var \Pozitim\CI\Exec\Adapter
     */
    protected $execHelper;

    /**
     * @param \Pozitim\CI\Exec\Adapter $execHelper
     */
    public function __construct(\Pozitim\CI\Exec\Adapter $execHelper)
    {
        $this->execHelper = $execHelper;
    }

    /**
     * @param $directory
     */
    public function createDirectory($directory)
    {
        $this->execHelper->exec(['mkdir', '-p', $directory]);
    }

    /**
     * @param $target
     */
    public function removePath($target)
    {
        $this->execHelper->exec(['rm', '-rf', $target]);
    }

    /**
     * @param $target
     * @param $destination
     */
    public function copy($target, $destination)
    {
        $this->execHelper->exec(['cp', '-r', $target, $destination]);
    }
}