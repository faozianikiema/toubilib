<?php
use gateway\actions\proxy\proxyAction;

 $config=require __DIR__ . '/bootstrap.php';
$client=$config['slim'];
$proxy=new proxyAction($client);

$app->get('/', function ( $request,$response) {
    $response->getBody()->write('API Gateway OK');
    return $response;
});

$app->get('/praticiens' , $proxy);
$app->get('/praticiens/{id}' ,$proxy);
$app->get('/praticiens/{id}/rendezvous',$proxy);



