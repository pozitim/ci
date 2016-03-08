<?php

namespace Pozitim\CI\Docker\Compose\Service;

class MemcachedServiceImpl extends ServiceAbstract
{
    /**
     * @return array
     */
    public function getDockerComposeContent()
    {
        return ['image' => 'memcached'];
    }
}
