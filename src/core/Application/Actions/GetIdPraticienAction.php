<?php
namespace App\core\Application\Actions;
use App\core\Application\Actions\AbstractAction;
use App\core\Application\ports\api\PraticienServiceInterphase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Uuid\Uuid;


// Example class to avoid empty namespace error
class GetIdPraticienAction extends AbstractAction {
    private PraticienServiceInterphase $praticienService;
    public function __construct(PraticienServiceInterphase $praticienService){
        $this->praticienService=$praticienService;
    }
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $id = $args['id'] ?? null;
       $praticien=$this->praticienService->getPraticien($id);
        $praticienArr=
             [
                'id'=>$praticien->getId(),
                'nom'=>$praticien->getNom(),
                'prenom'=>$praticien->getPrenom(),
                'ville'=>$praticien->getVille(),
                'email'=>$praticien->getEmail(),
                'telephone'=>$praticien->getTelephone(),
                'rpps_id'=>$praticien->getRppsid(),
                'titre'=>$praticien->getTitre(),
                'accepte_nouveau_patient'=>$praticien->getAccepteNouveauPatient(),
                'est_organisation'=>$praticien->getEstOrganisation()
            ];
        
        $response->getBody()->write((string)json_encode($praticienArr));
        return $response->withHeader('content-type','application/json');
    }

}
