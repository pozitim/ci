<?php

$environment = strtolower(getenv('APPLICATION_ENV'));
if (!$environment) {
    echo 'APPLICATION_ENV must be configured!' . PHP_EOL;
    exit(255);
}

$di = require_once(realpath(__DIR__ . '/../') . '/configs/bootstrap.php');
$di->get('config')->logger->default_name = 'console';
$di->get('error_catcher')->register();
$di->get('error_catcher')->setFatalCallback(function ($message) use ($di) {
    $di->get('logger_helper')->getLogger()->emergency($message);
});
$di->get('error_catcher')->setExceptionCallback(function (\Exception $exception) use ($di) {
    $di->get('logger_helper')->getLogger()->error($exception);
});

$doctrineConn = \Doctrine\DBAL\DriverManager::getConnection(
    array(
        'driver' => 'pdo_mysql',
        'pdo' => $di->get('pdo')
    )
);

$symfonyConsoleApp = new \Symfony\Component\Console\Application();
$symfonyConsoleApp->getHelperSet()->set(new \Pozitim\Console\DiHelper($di));
$symfonyConsoleApp->getHelperSet()->set(new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($doctrineConn));
$symfonyConsoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand());
$symfonyConsoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand());
$symfonyConsoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand());
$symfonyConsoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand());
$symfonyConsoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\LatestCommand());
$symfonyConsoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand());
$symfonyConsoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand());
$symfonyConsoleApp->add(new \Pozitim\CI\Console\ComposeRunnerCommand());
$symfonyConsoleApp->run();
