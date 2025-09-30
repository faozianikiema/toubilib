<?php
namespace App\core\Application\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\core\Application\DTO\InputRendezVousDTO;

class CreerRendezVousMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $bodyContent = (string) $request->getBody();
        $body = json_decode($bodyContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'JSON invalide']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Validation des champs obligatoires
        if (empty($body['praticienID']) || empty($body['date_heure_debut'])) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Champs obligatoires manquants']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Récupération du patient authentifié
        $patientId = $request->getHeaderLine('X-Patient-ID');
        if (!$patientId) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Patient non authentifié']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        // Conversion des dates
        try {
            $dateDebut = new \DateTime($body['date_heure_debut']);
        } catch (\Exception $e) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Création du DTO
        $dto = new InputRendezVousDTO(
            (string)$body['praticienID'],
            (string)$patientId,
            $dateDebut,
            $body['motif_visite'] ?? '',
            isset($body['duree']) ? (int)$body['duree'] : 30
        );

        // On attache le DTO à la requête
        $request = $request->withAttribute('dto', $dto);

        // Passer au prochain middleware / action
        return $handler->handle($request);
    }
}
