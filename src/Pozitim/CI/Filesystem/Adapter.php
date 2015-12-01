<?php

namespace Pozitim\CI\Filesystem;

interface Adapter
{
    /**
     * @param $directory
     */
    public function createDirectory($directory);

    /**
     * @param $target
     */
    public function removePath($target);

    /**
     * @param $target
     * @param $destination
     */
    public function copy($target, $destination);

    /**
     * @param $filePath
     * @param array $content
     */
    public function setYamlFileContent($filePath, array $content);

    /**
     * @param $filePath
     * @param $content
     * @param bool|false $append
     */
    public function setFileContent($filePath, $content, $append = false);

    /**
     * @param $filePath
     * @return array
     */
    public function getYamlFileContent($filePath);

    /**
     * @param $filePath
     * @return string
     */
    public function getFileContent($filePath);

}
