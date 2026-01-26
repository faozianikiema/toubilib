<?php
use GuzzleHttp\Client;
use gateway\Middlewares\CorsMiddleware;


$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add(new CorsMiddleware());


return [
    'slim' => new Client([
        'base_uri' => 'http://slim:8080'
        
    ])
];