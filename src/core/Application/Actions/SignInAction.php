<?php

namespace App\core\Application\Actions;

use App\core\Application\DTO\CredentialDTO;
use App\core\Application\Provider\AuthProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\core\Application\Actions\JsonRenderer;

class SignInAction {

    private AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider) {
        $this->authProvider = $authProvider;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $data = $request->getParsedBody();

        $credential = new CredentialDTO($data['email'], $data['password']);

        try {
            $auth_dto = $this->authProvider->signIn($credential);

        } catch (\Exception $e) {
            return JsonRenderer::render($response, 401, [
                'error' => $e->getMessage()
            ]);
        }

        return JsonRenderer::render($response, 200, $auth_dto);
    }
}
