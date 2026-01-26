<?php
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder=new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../../app/settings.php');

$c=$containerBuilder->build();
$app=AppFactory::createFromContainer($c);

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$settings =$c->get('settings');
$errorMiddleware = $app->addErrorMiddleware(
    $settings['displayErrorDetails'] ?? false, // <- seulement le booléen
    false,
    false
);
$errorHandler=$errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');
$errorHandler->registerErrorRenderer('application/json', function($exception, $displaysErrorDetails){
    return json_encode(['message'=>$exception->getMessage()]);
});
$app=(require __DIR__.'/routes.php')($app);
$routes=$app->getRouteCollector()->getRouteParser();

return $app;
