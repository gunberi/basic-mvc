<?php

define('SYSDIR', __DIR__ . '/system/');
define('ROOT', __DIR__ . '/');
define('IMG_PATH', __DIR__ . '/image/');
define('APPDIR', __DIR__ . '/app/');
define('VENDOR', __DIR__ . '/vendor/');

require_once(VENDOR . 'autoload.php');

use Sys\Core\Dispatcher;
$dispatch = new Dispatcher;
$dispatch->dispatch();
