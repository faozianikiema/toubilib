<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';


$app = AppFactory::create();

require __DIR__ . '/configuration/routes.php';



$app->run();