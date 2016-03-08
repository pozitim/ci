<?php

namespace Pozitim\CI\Notification\Sender;

use Monolog\Logger;
use OU\DI;
use Pozitim\CI\Database\Entity\NotificationTypeEntity;
use Zend\Config\Config;

abstract class SenderAbstract implements Sender
{
    /**
     * @var NotificationTypeEntity
     */
    protected $notificationTypeEntity;

    /**
     * @var \stdClass
     */
    protected $notificationTypeData;

    /**
     * @param NotificationTypeEntity $notificationTypeEntity
     */
    final public function __construct(NotificationTypeEntity $notificationTypeEntity)
    {
        $this->notificationTypeEntity = $notificationTypeEntity;
    }

    /**
     * @return Logger
     * @throws \Exception
     */
    protected function getLogger()
    {
        return $this->getDi()->get('logger_helper')->getLogger();
    }

    /**
     * @return DI
     */
    protected function getDi()
    {
        return $this->getNotificationTypeEntity()->getDi();
    }

    /**
     * @return \stdClass
     */
    protected function getNotificationTypeData()
    {
        if ($this->notificationTypeData == null) {
            $this->notificationTypeData = json_decode($this->getNotificationTypeEntity()->data);
        }
        return $this->notificationTypeData;
    }

    /**
     * @return NotificationTypeEntity
     */
    protected function getNotificationTypeEntity()
    {
        return $this->notificationTypeEntity;
    }

    /**
     * @return Config
     * @throws \Exception
     */
    protected function getConfig()
    {
        return $this->getDi()->get('config');
    }
}
