<?php
use gateway\application\actions\proxy\proxyAction;
use gateway\application\Middlewares\AuthMiddleware;

$config=require __DIR__ . '/bootstrap.php';
$clientPraticien=$config['app-praticien'];
$clientRdv=$config['rdv'];
$clientAuth=$config['app-auth'];
$authMiddleware = new AuthMiddleware($clientAuth);


$proxyRdv=new proxyAction($clientRdv,$clientAuth);
$proxyPraticien=new proxyAction($clientPraticien,$clientAuth);
$proxyAuth=new proxyAction($clientAuth,$clientAuth);

$app->get('/', function ( $request,$response) {
    $response->getBody()->write('API Gateway OK');
    return $response;
});

$app->get('/praticiens/{id}/rendezvous',$proxyRdv)->add($authMiddleware);
$app->patch('/rendezvous/{id}/status', $proxyRdv)->add($authMiddleware);
$app->post('/rendezvous',$proxyRdv)->add($authMiddleware); 
$app->delete('/rendezvous/{id}',$proxyRdv)->add($authMiddleware);
    $app->get('/praticiens/{id}/agenda',$proxyRdv)->add($authMiddleware);


$app->get('/praticiens',$proxyPraticien)->add($authMiddleware);

$app->get('/praticiens/{id}',$proxyPraticien)->add($authMiddleware);
$app->get('/praticiens/specialite/{specialite}',$proxyPraticien)->add($authMiddleware);
$app->get('/praticiens/ville/{ville}', $proxyPraticien)->add($authMiddleware);


$app->post('/auth/signIn',$proxyAuth);
$app->post('/auth/refresh',$proxyAuth);
$app->post('/auth/register',$proxyAuth);
$app->post('/auth/tokens/validate',$proxyAuth);

return $app;


