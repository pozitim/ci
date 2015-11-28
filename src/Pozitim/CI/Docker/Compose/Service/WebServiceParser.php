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
        $serviceConfig = ['image' => 'pozitim-ci/' . $suiteConfigs['image']];
        $serviceConfig = $this->prepareEnvironments($serviceConfig, $suiteConfigs);
        $serviceConfig = $this->prepareVolumes($serviceConfig, $suiteConfigs);
        $serviceConfig = $this->prepareCommand($serviceConfig, $suiteConfigs);
        $serviceConfig = $this->prepareLinks($serviceConfig, $suiteConfigs);
        return $serviceConfig;
    }

    /**
     * @param array $serviceConfig
     * @param array $suiteConfigs
     * @return array
     */
    public function prepareEnvironments(array $serviceConfig, array $suiteConfigs = [])
    {
        $publicFolder = '/project/public';
        $indexFile = 'index.php';
        if (isset($suiteConfigs['services']['web']['public_folder'])) {
            $publicFolder = $suiteConfigs['services']['web']['public_folder'];
        }
        if (isset($suiteConfigs['services']['web']['index_file'])) {
            $indexFile = $suiteConfigs['services']['web']['index_file'];
        }
        $env = isset($suiteConfigs['env']) ? $suiteConfigs['env'] : [];
        $env['NGINX_PUBLIC_FOLDER'] = $publicFolder;
        $env['NGINX_INDEX_FILE'] = $indexFile;
        $serviceConfig['environment'] = $env;
        return $serviceConfig;
    }

    /**
     * @param array $serviceConfig
     * @param array $suiteConfigs
     * @return array
     */
    public function prepareLinks(array $serviceConfig, array $suiteConfigs = [])
    {
        $links = [];
        $services = isset($suiteConfigs['services']) ? $suiteConfigs['services'] : [];
        foreach ($services as $serviceName => $serviceValues) {
            if ($serviceName == 'web') {
                continue;
            }
            $links[] = $serviceName . ':' . $serviceName;
        }
        if (!empty($links)) {
            $serviceConfig['links'] = $links;
        }
        return $serviceConfig;
    }

    /**
     * @param array $serviceConfig
     * @param array $suiteConfigs
     * @return array
     */
    public function prepareVolumes(array $serviceConfig, array $suiteConfigs = [])
    {
        $serviceConfig['volumes'] = ['./source-code:/project'];
        return $serviceConfig;
    }

    /**
     * @param array $serviceConfig
     * @param array $suiteConfigs
     * @return array
     */
    public function prepareCommand(array $serviceConfig, array $suiteConfigs = [])
    {
        $commands = [
            'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
            'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
            '/usr/sbin/php-fpm -R',
            '/usr/sbin/nginx'
        ];
        $commands = array_merge($commands, $suiteConfigs['scripts']);
        $serviceConfig['command'] = $commands;
        return $serviceConfig;
    }
}
