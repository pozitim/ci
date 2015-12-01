<?php

namespace Pozitim\CI\Filesystem;

class Helper implements Adapter
{
    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param $directory
     */
    public function createDirectory($directory)
    {
        $this->adapter->createDirectory($directory);
    }

    /**
     * @param $target
     */
    public function removePath($target)
    {
        $this->adapter->removePath($target);
    }

    /**
     * @param $target
     * @param $destination
     */
    public function copy($target, $destination)
    {
        $this->adapter->copy($target, $destination);
    }

    /**
     * @param $target
     * @param $content
     * @param bool|false $append
     */
    public function setFileContent($target, $content, $append = false)
    {
        $this->adapter->setFileContent($target, $content, $append);
    }

    /**
     * @param $target
     * @return string
     */
    public function getFileContent($target)
    {
        return $this->adapter->getFileContent($target);
    }

    /**
     * @param $filePath
     * @param array $content
     */
    public function setYamlFileContent($filePath, array $content)
    {
        $this->adapter->setYamlFileContent($filePath, $content);
    }

    /**
     * @param $filePath
     * @return array
     */
    public function getYamlFileContent($filePath)
    {
        return $this->adapter->getYamlFileContent($filePath);
    }
}
