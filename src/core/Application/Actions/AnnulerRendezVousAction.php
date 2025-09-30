<?php
namespace App\core\Application\Actions;
use App\core\Application\ports\api\RendezVousServiceInterphase;
use Exception;
use psr\Http\Message\RequestInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AnnulerRendezVousAction{
    private RendezVousServiceInterphase $service;

    public function __construct(RendezVousServiceInterphase $service){
        $this->service=$service;
    }
    public function __invoke(Response $response,Request $request,array $args){
        $id=$args['id']??null;
        if(!$id){
            $response->getBody()->write(json_encode(['error'=>'id de rendez vous manquant']));
            return $response->withHeader('Content-Type','application/json');
        }
        try {
            $this->service->annulerRendezVous($id);
            $response->getBody()->write(json_encode(['message'=> "Rendez-vous $id annulé avec succès"]));
            return $response->withHeader('Content-Type','Application/json');
        }catch(Exception $e){
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type','Application/json') ;       }
    }
}