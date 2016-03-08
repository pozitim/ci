<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/../'));
}

ini_set('display_errors', false);
date_default_timezone_set('Europe/Istanbul');

/**
 * Init
 */
$configs = array(
    'base_path' => BASE_PATH,
    'tmp_path' => BASE_PATH . '/tmp',
    'req_id' => uniqid('REQ-' . gethostname()),
    'php_bin' => '/usr/bin/php',
    'docker_bin' => '/usr/local/bin/docker',
    'docker_compose_bin' => '/usr/local/bin/docker-compose',
    'host_url' => 'http://192.168.99.100'
);

/**
 * PDO Service Configs
 */
$configs['pdo'] = array();
$configs['pdo']['dsn'] = 'mysql:host=127.0.0.1;dbname=ci;charset=utf8';
$configs['pdo']['hostname'] = '127.0.0.1';
$configs['pdo']['database'] = 'ci';
$configs['pdo']['username'] = 'root';
$configs['pdo']['password'] = '';

/**
 * Logger
 */
$configs['logger'] = array();
$configs['logger']['default_name'] = 'default';
$configs['logger']['default_path'] = realpath(BASE_PATH . '/log');
$configs['logger']['default_level'] = \Monolog\Logger::DEBUG;
/**
 * supports different path and level
 * $configs['logger']['app'] = array();
 * $configs['logger']['app']['path'] = realpath(BASE_PATH . '/log');
 * $configs['logger']['app']['level'] = \Monolog\Logger::DEBUG;
 * $di->getLogger('app')->info('foo bar');
 */

return $configs;
