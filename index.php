<?php


$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
ini_set('display_errors', 1);
define('SYSDIR', __DIR__ . '/system/');
define('ROOT', __DIR__ . '/');
define('IMG_PATH', __DIR__ . '/image/');
define('APPDIR', __DIR__ . '/app/');
define('VENDOR', __DIR__ . '/vendor/');

require_once(VENDOR . 'autoload.php');

use Sys\Core\Dispatcher;
$dispatch = new Dispatcher;
$dispatch->dispatch();

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$start = &$start;
//echo 'Time:' . round(($finish - $start), 4) . ' - Files: ' . sizeof(get_included_files());