<?php 
namespace Application\Middlewares;
 use Psr\Http\Message\ResponseInterface as Response;
 use Psr\Http\Message\ServerRequestInterface;

 class JsonRenderer {
    public static function render(Response $response,int $code, mixed $data=null):Response{
      $response=$response->withStatus($code)
                         ->withHeader('content-type','applicatin/json,charset=utf-8');
      if(!is_null($data)) $response->getBOdy()->write(json_encode($data));
      return $response;

    }
        
    }
  