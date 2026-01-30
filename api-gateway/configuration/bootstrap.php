<?php
declare(strict_types=1);
use GuzzleHttp\Client;
use gateway\application\Middlewares\CorsMiddleware;


$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add(new CorsMiddleware());


return [
    'app-auth' => new Client([
        'base_uri'=>'http://app-auth:8080']),

    'app-praticien' => new Client([
        'base_uri' => 'http://app-praticien:8080'
    ]),

    'rdv' => new Client([
        'base_uri' => 'http://rdv:8080'
    ]),
    // 'toubilib' => new Client([
    //     'base_uri' => 'http://toubilib:8080'
    // ]),

];
