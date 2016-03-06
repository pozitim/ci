<?php

namespace Pozitim\CI\Docker\Compose\Service;

class MysqlServiceImpl extends ServiceAbstract
{
    /**
     * @return array
     */
    public function getDockerComposeContent()
    {
        return [
            'image' => 'mysql',
            'environment' => [
                'MYSQL_DATABASE' => $this->getDatabaseName(),
                'MYSQL_ALLOW_EMPTY_PASSWORD' => 'yes'
            ]
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getDatabaseName()
    {
        $databaseName = $this->getServiceConfigValue('database');
        if ($databaseName == null) {
            throw new \Exception('service.mysql.database setting could not found!');
        }
        return $databaseName;
    }
}
