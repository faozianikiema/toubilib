<?php
declare(strict_types=1);
use App\core\Application\Actions\AnnulerRendezVousAction;
use App\core\Application\Actions\CreerRendezVousAction;
use App\core\Application\Actions\GetPatientAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\core\Application\Actions\GetAllPraticiensAction;
use App\core\Application\Actions\GetIdPraticienAction;
use App\core\Application\Actions\GetRendezVousAction;
use App\core\Application\Actions\GetRendezVousByIdAction;
use Slim\App;

return function(App $app):App{
    $app->get('/praticiens', GetAllPraticiensAction::class)
        ->setName('praticiens-list');
    $app->get('/praticiens/{id}', GetIdPraticienAction::class)
       ->setName('praticien');
    $app->get('/praticiens/{id}/rendezvous', GetRendezVousAction::class);
    $app->get('/rendezvous/{id}',GetRendezVousByIdAction::class)
        ->setName('rendezvous');

    $app->post('/rendezvous',CreerRendezVousAction::class);
    $app->delete('/rendezVous/{id}/annuler',AnnulerRendezVousAction::class);
    $app->get('/patients',GetPatientAction::class)
         ->setName('patient');

    $app->options('/{routes:.*}', function (Request $request, Response $response, $args): Response {
        return $response;
    });
    return $app;
};