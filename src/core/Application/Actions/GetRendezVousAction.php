<?php
namespace App\core\Application\Actions;

use App\core\Application\useCase\AuthzService;
use App\core\Application\UseCase\RendezVousService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\core\Application\ports\api\RendezVousServiceInterphase;

class GetRendezVousAction {
    // private RendezVousServiceInterphase $service;
    private RendezVousService $service;
    private AuthzService $authzservice;

    public function __construct(RendezVousService $service, AuthzService $authzservice) {
        $this->service = $service;
        $this->authzservice=$authzservice;
    }

   public function __invoke(Request $request, Response $response, array $args): Response
{
   /** @var \App\core\Application\DTO\ProfileDTO $profile */
   $profile=$request->getAttribute('profile');
   $praticienID = $args['id'] ?? null;
    if (!$praticienID) {
        $response->getBody()->write(json_encode(['error' => 'Praticien ID manquant']));
        return $response->withStatus(400)->withHeader('Content-Type','application/json');
    }
    if (!$this->authzservice->canAccessAgenda($profile, $praticienID)) {
            $response->getBody()->write(json_encode(['error' => 'Accès refusé']));
            return $response->withStatus(403)->withHeader('Content-Type','application/json');
        }

    // Récupérer les dates depuis query params si besoin
    $queryParams = $request->getQueryParams();
    $date_debut = isset($queryParams['date_debut']) ? new \DateTime($queryParams['date_debut']) : new \DateTime('today');
    $date_fin = isset($queryParams['date_fin']) ? new \DateTime($queryParams['date_fin']) : new \DateTime('+1 day');

    try {
        $rendezVousList = $this->service->getAgendaPraticien($praticienID, $date_debut, $date_fin);
    
       $data = array_map(fn($rdv) => [
    'id' => $rdv['id'],
    'patientID' => $rdv['patient_id'],
    'date_heure_debut' => $rdv['date_heure_debut'],
    'date_heure_fin' => $rdv['date_heure_fin'],
    'motif_visite' => $rdv['motif_visite'],
], $rendezVousList);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type','application/json');

    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(500)->withHeader('Content-Type','application/json');
    }
}

}
