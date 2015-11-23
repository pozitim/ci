<?php

$di = include(realpath(__DIR__ . '/../') . '/configs/bootstrap.php');
$di->get('class_loader')->add('Pozitim\\', realpath(__DIR__ . '/unit/src/'));
\Pozitim\DiSingleton::getInstance()->setDi($di);