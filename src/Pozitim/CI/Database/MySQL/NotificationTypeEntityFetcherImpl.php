<?php

namespace Pozitim\CI\Database\MySQL;

use Pozitim\CI\Database\NotificationTypeEntityFetcher;

class NotificationTypeEntityFetcherImpl extends MySQLAbstract implements NotificationTypeEntityFetcher
{
    /**
     * @return array
     */
    public function fetchAllObjects()
    {
        $sql = 'SELECT * FROM notification_type';
        $className = 'Pozitim\CI\Database\Entity\NotificationTypeEntity';
        return $this->getPDOHelper()->fetchAllObjects($sql, [], $className, [$this->getDi()]);
    }
}
