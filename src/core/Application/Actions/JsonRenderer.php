<?php
namespace App\core\Application\Actions;
use Psr\Http\Message\ResponseInterface;

class JsonRenderer {
    public static function render(ResponseInterface $response, int $status, mixed $data): ResponseInterface {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}