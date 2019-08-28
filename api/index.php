<?php

mb_internal_encoding('UTF-8');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// header("Access-Control-Allow-Methods: GET");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Methods: PUT");
// header("Access-Control-Allow-Methods: DELETE");

require_once 'autoloading.php';
require_once 'constants.php';

error_reporting(ERROR_SETTING);

spl_autoload_register('autoloading');

use Tulic\aPiE\Base\DiContainer;
use Tulic\aPiE\Base\Finalizer;

$di = new DiContainer();

$finalizer = new Finalizer($di->getService('Tulic\aPiE\Base\RouterController'), Finalizer::MODE_JSON);

echo $finalizer;
