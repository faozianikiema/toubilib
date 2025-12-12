<?php
namespace App\Core\Application\Actions;

use App\core\Application\ports\spi\RendezVousRepository;
use App\core\Application\UseCase\RendezVousService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as SlimResponse;
use App\core\Application\DTO\ProfileDTO;

class GetPatientConsultationsAction
{
    private RendezVousService $rdvRepository;

    public function __construct(RendezVousService $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        /** @var ProfileDTO $profile */
        $profile = $request->getAttribute('profile');

        if (!$profile) {
            $response->getBody()->write(json_encode(['error' => 'Utilisateur non authentifié']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $patientId = $args['id'] ?? null;
        if (!$patientId) {
            $response->getBody()->write(json_encode(['error' => 'ID du patient requis']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Autorisation : le patient authentifié doit être celui dont on demande l'historique
        if ($profile->getId() !== $patientId) {
            $response->getBody()->write(json_encode(['error' => 'Accès refusé']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        // Récupérer l'historique des consultations
        $consultations = $this->rdvRepository->GetPratienRendezVous($patientId);

        $response->getBody()->write(json_encode($consultations));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
