<?php

namespace Pozitim\CI\Database;

interface NotificationEntityFetcher
{
    /**
     * @return array
     */
    public function fetchAllObjects();
}
