<?php
namespace App\core\Application\Actions;
use App\core\Application\ports\api\RendezVousServiceInterphase;
use Exception;
use psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AnnulerRendezVousAction{
    private RendezVousServiceInterphase $service;

    public function __construct(RendezVousServiceInterphase $service){
        $this->service=$service;
    }
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $idRdv = $args['id'] ?? null;

        if (!$idRdv) {
            $response->getBody()->write(json_encode(['error' => 'ID de rendez-vous manquant']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $this->service->annulerRendezVous($idRdv);

            $response->getBody()->write(json_encode(['message' => 'Rendez-vous annulé avec succès']));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');

        } catch (\InvalidArgumentException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');

        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}