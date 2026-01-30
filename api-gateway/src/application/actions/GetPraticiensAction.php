<?php

namespace gateway\application\actions;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetPraticiensAction{
    private Client $client;
    public function __construct(Client $client){
        $this->client=$client;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $apiResponse=$this->client->get('/praticiens');
         $response->getBody()->write(
            $apiResponse->getBody()->getContents()
        );

        // recopier le Content-Type
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($apiResponse->getStatusCode());
    

    }

}