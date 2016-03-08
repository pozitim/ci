<?php

namespace Pozitim\CI\Notification\Sender;

use Pozitim\CI\Suite;

interface Sender
{
    /**
     * @param Suite $suite
     */
    public function sendJobCompletedNotification(Suite $suite);
}
