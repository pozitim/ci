<?php

namespace Pozitim\CI\Docker\Compose\Service;

class WebServiceParser implements ServiceParser
{
    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function parse(array $suiteConfigs)
    {
        return [
            'image' => 'pozitim-ci/' . $suiteConfigs['image'],
            'environment' => $this->prepareEnvironments($suiteConfigs),
            'links' => $this->prepareLinks($suiteConfigs),
            'volumes' => $this->prepareVolumes($suiteConfigs),
            'command' => $this->prepareCommand($suiteConfigs)
        ];
    }

    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function prepareEnvironments(array $suiteConfigs = [])
    {
        $publicFolder = '/project/public';
        $indexFile = 'index.php';
        if (isset($suiteConfigs['services']['web']['public_folder'])) {
            $publicFolder = $suiteConfigs['services']['web']['public_folder'];
        }
        if (isset($suiteConfigs['services']['web']['index_file'])) {
            $indexFile = $suiteConfigs['services']['web']['index_file'];
        }
        $environments = [
            'NGINX_PUBLIC_FOLDER' => $publicFolder,
            'NGINX_INDEX_FILE' => $indexFile
        ];
        return array_merge($environments, isset($suiteConfigs['env']) ? $suiteConfigs['env'] : []);
    }

    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function prepareLinks(array $suiteConfigs = [])
    {
        $links = [];
        foreach ($suiteConfigs['services'] as $serviceName => $serviceValues) {
            if ($serviceName == 'web') {
                continue;
            }
            $links[] = $serviceName . ':' . $serviceName;
        }
        return $links;
    }

    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function prepareVolumes(array $suiteConfigs = [])
    {
        return ['./source-code:/project'];
    }

    /**
     * @param array $suiteConfigs
     * @return array
     */
    public function prepareCommand(array $suiteConfigs = [])
    {
        $commands = [
            'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
            'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
            '/usr/sbin/php-fpm -R',
            '/usr/sbin/nginx'
        ];
        return array_merge($commands, $suiteConfigs['scripts']);
    }
}
