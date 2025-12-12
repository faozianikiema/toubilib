<?php

namespace App\core\Application\Middleware;
use App\core\Application\Provider\jwt\JwtManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;
use App\core\Application\DTO\ProfileDTO;
use App\core\Application\usecase\JwtService;
use App\core\Application\ports\spi\UserRepository;

class AuthnMiddleware 
{
    private JwtManager $jwtManager;
    private UserRepository $userRepository;
    public function __construct( JwtManager $jwtManager,  UserRepository $userRepository) {
        $this->jwtManager=$jwtManager;
        $this->userRepository=$userRepository;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->unauthorizedResponse('Token manquant');
        }

        $token = substr($authHeader, 7);

        try {
           $payload = $this->jwtManager->validate($token);
        $userId = $payload['userId'] ?? null;

        if (!$userId) {
            return $this->unauthorizedResponse('Utilisateur non authentifié');
        }
        $user = $this->userRepository->findById($userId);

            $profile = new ProfileDTO(
                $user->getId(),
                $user->getEmail(),
                $user->getRole()
            );

            // On attache le DTO à la requête pour les actions suivantes
            $request = $request->withAttribute('profile', $profile);

            return $handler->handle($request);

        } catch (\Exception $e) {
            return $this->unauthorizedResponse('Token invalide : ' . $e->getMessage());
        }
    }

    private function unauthorizedResponse(string $message): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode([
            'error' => $message
        ]));
        return $response->withStatus(401)
                        ->withHeader('Content-Type', 'application/json');
    }
}