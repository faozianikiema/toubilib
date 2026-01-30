<?php
namespace gateway\application\Middlewares;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

class AuthMiddleware
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke( ServerRequestInterface $request,RequestHandlerInterface $handler ): ResponseInterface {

        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new HttpUnauthorizedException(
                $request,
                'Access token manquant'
            );
        }

        $token = substr($authHeader, 7);

        try {
            $this->client->request('POST', '/auth/tokens/validate', [
                'json' => ['token' => $token]
            ]);
        }
        catch (ClientException $e) {
            throw new HttpUnauthorizedException(
                $request,
                'Access token invalide'
            );
        }
        catch (ConnectException | ServerException $e) {
            throw new HttpUnauthorizedException(
                $request,
                'Service d’authentification indisponible'
            );
        }

        return $handler->handle($request);
    }
}
