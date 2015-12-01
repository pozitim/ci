<?php

namespace Pozitim\CI\Filesystem\Adapter;

use Pozitim\CI\Filesystem\Adapter;
use Symfony\Component\Yaml\Yaml;

abstract class AdapterAbstract implements Adapter
{
    /**
     * @param $filePath
     * @param array $content
     */
    public function setYamlFileContent($filePath, array $content)
    {
        $content = Yaml::dump($content);
        $this->setFileContent($filePath, $content);
    }

    /**
     * @param $filePath
     * @param $content
     * @param bool|false $append
     */
    public function setFileContent($filePath, $content, $append = false)
    {
        $flags = null;
        if ($append) {
            $flags = FILE_APPEND;
        }
        file_put_contents($filePath, $content, $flags);
    }

    /**
     * @param $filePath
     * @return array
     */
    public function getYamlFileContent($filePath)
    {
        return (array)Yaml::parse($this->getFileContent($filePath));
    }

    /**
     * @param $filePath
     * @return string
     */
    public function getFileContent($filePath)
    {
        return file_get_contents($filePath);
    }
}
