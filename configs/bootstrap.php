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

$di->setShared('filesystem_helper', function (\OU\DI $di) {
    $execHelper = $di->get('exec_helper');
    $adapter = new \Pozitim\CI\Filesystem\Adapter\ExecAdapter($execHelper);
    return new \Pozitim\CI\Filesystem\Helper($adapter);
});

$di->setShared('exec_helper', function (\OU\DI $di) {
    $logger = $di->get('logger_helper')->getLogger();
    $adapter = new \Pozitim\CI\Exec\Adapter\LocalAdapter();
    return new \Pozitim\CI\Exec\Helper($adapter, $logger);
});

$di->setShared('config_parser', function (\OU\DI $di) {
    return new \Pozitim\CI\Config\ConfigParser($di->get('filesystem_helper'));
});

$di->setShared('docker_compose_runner', function (\OU\DI $di) {
    return new \Pozitim\CI\Docker\Compose\ComposeRunner($di);
});

$di->setShared('docker_compose_settings_generator', function (\OU\DI $di) {
    return new \Pozitim\CI\Docker\Compose\ComposeSettingsGenerator($di);
});

return $di;
