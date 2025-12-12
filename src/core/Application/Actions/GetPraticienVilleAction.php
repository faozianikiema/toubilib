<?php
namespace App\Core\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Core\Application\UseCase\PraticienService;

class GetPraticienVilleAction {

    private PraticienService $service;

    public function __construct(PraticienService $service) {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        // Récupère le paramètre "ville" depuis la query string
         $ville= $args['ville'] ?? null;

        if (empty($ville)) {
            $response->getBody()->write(json_encode([
                'error' => 'Le paramètre ville est requis.'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        // Appelle le service pour obtenir les praticiens
        $praticiens = $this->service->GetPraticienByVille($ville);

        // Retourne la réponse JSON
        $response->getBody()->write(json_encode($praticiens));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
