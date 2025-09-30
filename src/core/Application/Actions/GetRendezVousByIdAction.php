<?php
namespace App\core\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\core\Application\ports\api\RendezVousServiceInterphase;

class GetRendezVousByIdAction {
    private RendezVousServiceInterphase $service;

    public function __construct(RendezVousServiceInterphase $service) {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        $id = $args['id'] ?? null;

        if (!$id) {
            $response->getBody()->write(json_encode(['error' => 'ID manquant']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $rdv = $this->service->findById($id);

            $data = [
                'id' => $rdv->getId(),
                'praticien_id' => $rdv->getPraticienID(),
                'patient_id' => $rdv->getPatientID(),
                'date_heure_debut' => $rdv->getDate_heure_debut()->format('Y-m-d H:i:s'),
                'date_heure_fin' => $rdv->getDate_heure_fin()->format('Y-m-d H:i:s'),
                'motif_visite' => $rdv->getMotif_visite()
            ];

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
    }
}

