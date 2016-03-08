<?php

namespace Pozitim\CI\Notification\Sender;

use Pozitim\CI\Database\Entity\NotificationTypeEntity;

class SenderFactory
{
    public static function factory(NotificationTypeEntity $notificationType)
    {
        switch ($notificationType->type) {
            case 'hipchat':
                return new HipChatSender($notificationType);
                break;
        }
    }
}
