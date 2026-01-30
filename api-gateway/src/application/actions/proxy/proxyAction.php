<?php
namespace gateway\application\actions\proxy;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Stream;
use function DI\string;

class proxyAction {
    private Client $remoteService;
    private Client $authclient;

    public function __construct(Client $client, Client $authClient) {
        $this->remoteService = $client;
        $this->authclient = $authClient;
    }

public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
    $path = $request->getUri()->getPath();  
    $method = $request->getMethod();
    $body = (string)$request->getBody();
    
    try {
        if (in_array($path, ['/auth/register', '/auth/signIn'])) {
            $apiResponse = $this->remoteService->request($method, $path, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => $body
            ]);

            $bodyContent = $apiResponse->getBody()->getContents();
            $response->getBody()->write($bodyContent);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $jwt = $request->getHeaderLine('Authorization');
        if (empty($jwt)) {
            throw new \Slim\Exception\HttpUnauthorizedException($request, 'Token non fourni');
        }

        $validationResponse = $this->authclient->request('POST', '/auth/tokens/validate', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => ['token' => str_replace('Bearer ', '', $jwt)]
        ]);

        if ($validationResponse->getStatusCode() !== 200) {
            throw new \Slim\Exception\HttpUnauthorizedException($request, 'Token invalide');
        }

        $apiResponse = $this->remoteService->request($method, $path, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $body
        ]);

        $bodyContent = $apiResponse->getBody()->getContents();
        $response->getBody()->write($bodyContent);
        return $response->withHeader('Content-Type', 'application/json');

    } catch (ServerException $e) {
        $streamFactory = new \Slim\Psr7\Factory\StreamFactory();
        $bodyStream = $streamFactory->createStream(json_encode([
            'error' => 'Remote service unavailable'
        ]));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(502)
                        ->withBody($bodyStream);
    }
}
}