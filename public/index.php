<?php

$environment = strtolower(getenv('APPLICATION_ENV'));
if (!$environment) {
    echo 'APPLICATION_ENV must be configured!' . PHP_EOL;
    exit(255);
}

/**
 * @var \OU\DI $di
 */
$di = include(realpath(__DIR__ . '/../') . '/configs/bootstrap.php');
$dispatcher = $di->get('dispatcher');
$dispatcher->dispatch();
