<?php

$file = __DIR__.'/../vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite. "php composer.phar install --dev"');
}

$loader = require $file;

$loader->loadClass('Net_SSH');
$loader->loadClass('Net_SFTP');

define('NET_SSH_LOGGING',  NET_SSH2_LOG_COMPLEX);
define('NET_SFTP_LOGGING', NET_SSH2_LOG_COMPLEX);
