<?php

namespace Pozitim\CI\Docker\Compose\Service;

class MongoServiceImpl extends ServiceAbstract
{
    /**
     * @return array
     */
    public function getDockerComposeContent()
    {
        return ['image' => 'mongo'];
    }
}
