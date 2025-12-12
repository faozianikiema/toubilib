<?php
namespace App\Core\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Core\Application\UseCase\PraticienService;

class GetPraticienSpecialiteAction {

    private PraticienService $service;

    public function __construct(PraticienService $service) {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        // Récupération du paramètre "specialite" depuis la query string
           $specialite=$args['specialite']?? null;
        if (empty($specialite)) {
            $response->getBody()->write(json_encode([
                'error' => 'Le paramètre specialite est requis.'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        // Appel du service pour obtenir les praticiens
        $praticiens = $this->service->GetPraticienBySpecialite($specialite);

        // Retour JSON
        $response->getBody()->write(json_encode($praticiens));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
