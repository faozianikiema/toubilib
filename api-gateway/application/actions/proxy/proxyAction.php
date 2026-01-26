<?php
namespace gateway\actions\proxy;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class proxyAction {
    private Client $remoteService;

    public function __construct(Client $client) {
        $this->remoteService = $client;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $path = $request->getUri()->getPath();  
        $method = $request->getMethod();
        $body = $request->getBody()->getContents();
        $headers = $request->getHeaders();
        try{
                 

        $apiResponse = $this->remoteService->request($method, $path, [
                'headers' => $headers,
                'body' => $body,
            ]);

        $body = $apiResponse->getBody()->getContents();

        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');


        }catch (ServerException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            if (str_contains(strtolower($body), 'praticien not found')) {
                throw new HttpNotFoundException($request, "Praticien {$path} non trouvé");
            }
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
