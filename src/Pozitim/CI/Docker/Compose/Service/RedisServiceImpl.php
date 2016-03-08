<?php

namespace Pozitim\CI\Docker\Compose\Service;

class RedisServiceImpl extends ServiceAbstract
{
    /**
     * @return array
     */
    public function getDockerComposeContent()
    {
        return ['image' => 'redis'];
    }
}
