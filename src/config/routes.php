<?php
declare(strict_types=1);
use App\core\Application\Actions\AnnulerRendezVousAction;
use App\core\Application\Actions\CreerRendezVousAction;
use App\core\Application\Actions\GetPatientAction;
use App\Core\Application\Actions\GetPatientConsultationsAction;
use App\Core\Application\Actions\GetPraticienRendezVousAction;
use App\Core\Application\Actions\GetPraticienSpecialiteAction;
use App\Core\Application\Actions\GetPraticienVilleAction;
use App\Core\Application\Actions\RegisterPatientAction;
use App\core\Application\Actions\SignInAction;
use App\Core\Application\Actions\UpdateStatusAction;
use App\core\Application\Middleware\AuthnMiddleware;
use App\core\Application\Middleware\AuthzMiddleware;
use App\core\Application\Middleware\CreerRendezVousMiddleware;
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
    $app->get('/praticiens/specialite/{specialite}',GetPraticienSpecialiteAction::class);
    $app->get('/praticiens/ville/{ville}', GetPraticienVilleAction::class);

    $app->get('/praticiens/{id}/rendezvous', GetPraticienRendezVousAction::class)->add(AuthnMiddleware::class);
    $app->get('/rendezvous/{id}',GetRendezVousByIdAction::class)
        ->setName('rendezvous');
    $app->patch('/rendezvous/{id}/status', UpdateStatusAction::class)->add(AuthnMiddleware::class);

    $app->post('/rendezvous',CreerRendezVousAction::class)
        ->add(CreerRendezVousMiddleware::class);
    $app->get('/patients/{id}/consultations', GetPatientConsultationsAction::class)
    ->add(AuthnMiddleware::class);
    $app->delete('/rendezvous/{id}',AnnulerRendezVousAction::class);
    $app->get('/patients',GetPatientAction::class)
         ->setName('patient');
    $app->post('/patients',RegisterPatientAction::class);
    $app->post('/signIn', SignInAction::class);
    $app->get('/praticiens/{id}/agenda',GetRendezVousAction::class)
        ->add(AuthnMiddleware::class)
        ->add(AuthzMiddleware::class);

    // $app->options('/{routes:.*}', function (Request $request, Response $response, $args): Response {
    //     return $response;
    // });
    return $app;
};