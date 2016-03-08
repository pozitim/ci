<?php

namespace Pozitim\CI\Docker\Compose\Service;

class GearmandServiceImpl extends ServiceAbstract
{
    /**
     * @return array
     */
    public function getDockerComposeContent()
    {
        return ['image' => 'pataquest/gearmand'];
    }
}
