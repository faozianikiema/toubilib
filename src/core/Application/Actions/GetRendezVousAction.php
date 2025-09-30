<?php
namespace App\core\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\core\Application\ports\api\RendezVousServiceInterphase;

class GetRendezVousAction {
    private RendezVousServiceInterphase $service;

    public function __construct(RendezVousServiceInterphase $service) {
        $this->service = $service;
    }

   public function __invoke(Request $request, Response $response, array $args): Response
{
    $praticienID = $args['id'] ?? null;
    $id = $args['id'] ?? null;
    if (!$praticienID) {
        $response->getBody()->write(json_encode(['error' => 'Praticien ID manquant']));
        return $response->withStatus(400)->withHeader('Content-Type','application/json');
    }

    // Récupérer les dates depuis query params si besoin
    $queryParams = $request->getQueryParams();
    $dateDebut = isset($queryParams['date_debut']) ? new \DateTime($queryParams['date_debut']) : new \DateTime('today');
    $dateFin = isset($queryParams['date_fin']) ? new \DateTime($queryParams['date_fin']) : new \DateTime('+1 day');

    try {
        $rendezVousList = $this->service->findByPraticienOfPeriod($praticienID, $dateDebut, $dateFin);
    
        $data = array_map(fn($rdv) => [
            'id' => $rdv->getId(),
            'patientID' => $rdv->getPatientID(),
            'date_heure_debut' => $rdv->getDate_heure_debut()->format('Y-m-d H:i:s'),
            'date_heure_fin' => $rdv->getDate_heure_fin()->format('Y-m-d H:i:s'),
            'motif_visite' => $rdv->getMotif_visite()
        ], $rendezVousList);

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type','application/json');

    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(500)->withHeader('Content-Type','application/json');
    }
}

}
