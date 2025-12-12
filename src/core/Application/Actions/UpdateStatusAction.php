<?php
namespace App\Core\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Core\Application\UseCase\RendezVousService;

class UpdateStatusAction
{
    private RendezVousService $service;

    public function __construct(RendezVousService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $rdvId = $args['id'] ?? null;
        $data = json_decode($request->getBody()->getContents(), true);
        $status = $data['status'] ?? null;

        if (!$rdvId || !$status) {
            $response->getBody()->write(json_encode([
                'error' => 'ID du rendez-vous et status sont requis.'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        if (!in_array($status, ['honoré', 'non honoré'])) {
            $response->getBody()->write(json_encode([
                'error' => 'Statut invalide. Utilisez "honoré" ou "non_honoré".'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Vérifier que le praticien authentifié correspond à celui du RDV
        $currentUser = $request->getAttribute('user'); 
        $rdv = $this->service->findById($rdvId);

        if ($currentUser['role'] !== '10' || $currentUser['id'] !==  $rdv->getPraticienID()) {
            $response->getBody()->write(json_encode([
                'error' => 'Vous n’êtes pas autorisé à modifier ce rendez-vous.'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }

        // Mise à jour du statut
        $this->service->updateStatus($rdvId, $status);

        $response->getBody()->write(json_encode([
            'message' => "Statut du rendez-vous mis à jour avec succès.",
            'status' => $status
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
