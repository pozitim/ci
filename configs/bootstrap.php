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

$di->setShared('pdo', function (\OU\DI $di) {
    $config = $di->get('config');
    try {
        $pdo = new \PDO($config->pdo->dsn, $config->pdo->username, $config->pdo->password);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $exception) {
        /**
         * @var \Monolog\Logger $logger
         */
        $logger = $di->get('logger_helper')->getLogger();
        $logger->critical($exception);
        throw $exception;
    }
    return $pdo;
});

$di->setShared('pdo_helper', function (\OU\DI $di) {
    $profiler = new \Pozitim\MySQL\SQLProfiler($di);
    $helper = new \Pozitim\MySQL\PDOHelper($di);
    $helper->setProfiler($profiler);
    return $helper;
});

$di->setShared('build_entity_saver', function (\OU\DI $di) {
    return new \Pozitim\CI\Database\MySQL\BuildEntitySaverImpl($di);
});

$di->setShared('job_entity_saver', function (\OU\DI $di) {
    return new \Pozitim\CI\Database\MySQL\JobEntitySaverImpl($di);
});

$di->setShared('job_entity_fetcher', function (\OU\DI $di) {
    return new \Pozitim\CI\Database\MySQL\JobEntityFetcherImpl($di);
});

$di->setShared('notification_type_entity_fetcher', function (\OU\DI $di) {
    return new \Pozitim\CI\Database\MySQL\NotificationTypeEntityFetcherImpl($di);
});

$di->setShared('temporary_folder_setup_helper', function (\OU\DI $di) {
    return new \Pozitim\CI\Docker\Compose\TemporaryFolderSetupHelper($di->get('config')->tmp_path);
});

$di->setShared('http_request', function ($di) {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
});

$di->setShared('http_response', function ($di) {
    return new \Symfony\Component\HttpFoundation\Response();
});

$di->setShared('router', function (\OU\DI $di) {
    $router = new AltoRouter();
    $router->map('GET', '/raw-job-viewer', '\Pozitim\CI\Web\V1\RawJobViewerController#view');
    $router->map('POST|GET|PUT|DELETE', '*', '\Pozitim\CI\Web\V1\NotFoundController#notFound');
    return $router;
});

$di->setShared('dispatcher', function (\OU\DI $di) {
    return new \Pozitim\CI\Web\Dispatcher($di);
});

$di->setShared('notification_sender', function (\OU\DI $di) {
    return new \Pozitim\CI\Notification\NotificationSender($di);
});

return $di;
