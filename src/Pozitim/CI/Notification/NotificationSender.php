<?php

namespace Pozitim\CI\Notification;

use OU\DI;
use Pozitim\CI\Database\NotificationTypeEntityFetcher;
use Pozitim\CI\Notification\Sender\Sender;
use Pozitim\CI\Notification\Sender\SenderFactory;
use Pozitim\CI\Suite;

class NotificationSender implements Sender
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @var array
     */
    protected $notificationTypeSenders = array();

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    /**
     * @param Suite $suite
     */
    public function sendJobCompletedNotification(Suite $suite)
    {
        /**
         * @var Sender $notificationTypeSender
         */
        foreach ($this->getNotificationTypeSender() as $notificationTypeSender) {
            $notificationTypeSender->sendJobCompletedNotification($suite);
        }
    }

    /**
     * @return array
     */
    protected function getNotificationTypeSender()
    {
        if (empty($this->notificationTypeSenders)) {
            $notificationTypes = $this->getNotificationTypeEntityFetcher()->fetchAllObjects();
            foreach ($notificationTypes as $notificationType) {
                $this->notificationTypeSenders[] = SenderFactory::factory($notificationType);
            }
        }
        return $this->notificationTypeSenders;
    }

    /**
     * @return NotificationTypeEntityFetcher
     * @throws \Exception
     */
    protected function getNotificationTypeEntityFetcher()
    {
        return $this->getDi()->get('notification_type_entity_fetcher');
    }

    /**
     * @return DI
     */
    protected function getDi()
    {
        return $this->di;
    }
}
