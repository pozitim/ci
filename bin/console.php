<?php

$cmdOptions = getopt('', ['env:']);
$environment = isset($cmdOptions['env']) ? $cmdOptions['env'] : strtolower(getenv('APPLICATION_ENV'));

// Check app_env setting.
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

$symfonyConsoleApp = new \Symfony\Component\Console\Application();
$symfonyConsoleApp->getHelperSet()->set(new \Pozitim\Console\DiHelper($di));
$symfonyConsoleApp->add(new \Pozitim\CI\Console\ComposeRunnerCommand());
$symfonyConsoleApp->run();
