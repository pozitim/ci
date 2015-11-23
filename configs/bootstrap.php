<?php

$loader = include(realpath(__DIR__ . '/../') . '/vendor/autoload.php');
error_reporting(E_ALL);
$environment = !isset($environment) ? 'development' : $environment;
ini_set('error_log', realpath(__DIR__ . '/../log/') . '/php_error.log');

$di = new \OU\DI();
$di->setShared('class_loader', $loader);

$di->setShared('config', function () use ($environment) {
    $filePath = realpath(__DIR__ . '/env/' . $environment . '.php');
    $configs = new \Zend\Config\Config(include($filePath), true);
    $configs->environment = $environment;
    return $configs;
});

$di->setShared('logger_helper', function ($di) {
    return new \Pozitim\Logger\MonologHelper($di);
});

$di->setShared('error_catcher', function () {
    return new \Pozitim\ErrorCatcher();
});

return $di;
