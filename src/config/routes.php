<?php
declare(strict_types=1);
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\core\Application\Actions\GetAllPraticiensAction;
use Slim\App;

return function(App $app):App{
    $app->get('/praticiens', GetAllPraticiensAction::class)
        ->setName('praticiens-list');

    $app->options('/{routes:.*}', function (Request $request, Response $response, $args): Response {
        return $response;
    });
    return $app;
};