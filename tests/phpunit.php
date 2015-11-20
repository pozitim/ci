<?php

$di = include(realpath(__DIR__ . '/../') . '/configs/bootstrap.php');
$di->getClassLoader()->add('Pozitim\\', realpath(__DIR__ . '/unit/src/'));
\Pozitim\DiSingleton::getInstance()->setDi($di);
