<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../app/config/config.php';
require __DIR__.'/../app/config/common.php';
require __DIR__.'/../app/routes/routes.php';



$app->run();
