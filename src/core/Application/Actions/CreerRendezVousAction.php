<?php
namespace App\core\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\core\Application\ports\api\RendezVousServiceInterphase;
use App\core\Application\DTO\InputRendezVousDTO;

class CreerRendezVousAction {
    private RendezVousServiceInterphase $service;

    public function __construct(RendezVousServiceInterphase $service) {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response): Response {
        
        $dto = $request->getAttribute('dto');

        if (!$dto instanceof InputRendezVousDTO) {
            $response->getBody()->write(json_encode([
                'error' => 'Données du rendez-vous manquantes ou invalides'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            // Appel du service pour créer le rendez-vous
            $this->service->creerRendezVous($dto);

            $response->getBody()->write(json_encode([
                'message' => 'Rendez-vous créé avec succès'
            ]));
            return $response
                ->withStatus(201) // Code HTTP pour "Created"
                ->withHeader('Content-Type', 'application/json');

        } catch (\InvalidArgumentException $e) {
            // Cas d’erreur de validation métier
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {
            // Cas d’erreur serveur ou base de données
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
