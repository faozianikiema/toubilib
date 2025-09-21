<?php
namespace App\core\Application\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractAction{
    abstract public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $array): ResponseInterface;
}