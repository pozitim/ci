<?php

namespace Pozitim\CI\Docker\Compose\Service;

class DefaultServiceImpl extends ServiceAbstract
{
    /**
     * @var array
     */
    protected $context = [];

    /**
     * @return array
     */
    public function getDockerComposeContent()
    {
        $this->configureDefault();
        $this->configureEnvironments();
        $this->configureLinks();
        $this->configureCommand();
        return $this->context;
    }

    public function configureDefault()
    {
        $this->context = [
            'image' => $this->getSuite()->getImage(),
            'volumes' => ['./source-code:/project', './init.sh:/init.sh'],
            'command' => [
                'sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf',
                'sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf',
                '/usr/sbin/php-fpm -R',
                '/usr/sbin/nginx'
            ]
        ];
    }

    public function configureEnvironments()
    {
        $defaultPublicFolder = '/project/public';
        $defaultIndexFile = 'index.php';
        $environments = $this->getSuite()->getEnvironments();
        $environments['NGINX_PUBLIC_FOLDER'] = $this->getServiceConfigValue('public_folder', $defaultPublicFolder);
        $environments['NGINX_INDEX_FILE'] = $this->getServiceConfigValue('index_file', $defaultIndexFile);
        $this->context['environment'] = $environments;
    }

    public function configureLinks()
    {
        /**
         * @var Service $service
         */
        $links = [];
        foreach ($this->getSuite()->getServices() as $service) {
            if (!$service instanceof DefaultServiceImpl) {
                $links[] = $service->getServiceName();
            }
        }
        if (!empty($links)) {
            $this->context['links'] = $links;
        }
    }

    public function configureCommand()
    {
        $this->context['command'] = array_merge($this->context['command'], $this->getSuite()->getCommands());
    }
}
