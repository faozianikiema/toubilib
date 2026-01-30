<?php
namespace gateway\application\actions;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class GetPraticienByIDAction {
    private Client $remoteService;

    public function __construct(Client $client) {
        $this->remoteService = $client;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
           $id = $args['id']; 
        try{
                 

        $apiResponse = $this->remoteService->get("/praticiens/{$id}");
        $body = $apiResponse->getBody()->getContents();

        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');


        }catch (ServerException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            if (str_contains(strtolower($body), 'praticien not found')) {
                throw new HttpNotFoundException($request, "Praticien {$id} non trouvé");
            }
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
