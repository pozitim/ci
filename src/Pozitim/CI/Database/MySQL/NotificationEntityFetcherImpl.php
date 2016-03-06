<?php

namespace Pozitim\CI\Database\MySQL;

use Pozitim\CI\Database\NotificationEntityFetcher;

class NotificationEntityFetcherImpl extends MySQLAbstract implements NotificationEntityFetcher
{
    /**
     * @return array
     */
    public function fetchAllObjects()
    {
        $sql = 'SELECT * FROM notification';
        $className = 'Pozitim\CI\Database\Entity\NotificationEntity';
        return $this->getPDOHelper()->fetchAllObjects($sql, [], $className, [$this->getDi()]);
    }
}
