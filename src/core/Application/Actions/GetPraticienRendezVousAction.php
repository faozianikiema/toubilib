<?php
namespace App\Core\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Core\Application\UseCase\RendezVousService;

class GetPraticienRendezVousAction
{
    private RendezVousService $rendezVousService;

    public function __construct(RendezVousService $rendezVousService)
    {
        $this->rendezVousService = $rendezVousService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $praticienId = $args['id'] ?? null;

        if (!$praticienId) {
            $response->getBody()->write(json_encode([
                'error' => 'L\'ID du praticien est requis.'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $rendezVousList = $this->rendezVousService->GetPratienRendezVous($praticienId);

            // Conversion des objets en tableau pour JSON
           $rendezVousArray = array_map(function($rdv) {
                return [
                    'id' => $rdv['id'],
                    'patient' => [
                        'id' => $rdv['patient_id'],
                        'nom' => $rdv['patient_nom'],
                        'prenom' => $rdv['patient_prenom']
                    ],
                    'date_heure_debut' => $rdv['date_heure_debut'],
                    'date_heure_fin' => $rdv['date_heure_fin'],
                    'statut' => $rdv['statut'],
                    'motif_visite' => $rdv['motif_visite'] ?? null
                ];
            }, $rendezVousList);

            $response->getBody()->write(json_encode($rendezVousArray));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
