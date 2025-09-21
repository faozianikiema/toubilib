<?php
namespace App\core\Application\Actions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\core\Application\ports\api\PraticienServiceInterphase;
use App\core\Application\Actions\AbstractAction;



class GetAllPraticiensAction extends AbstractAction
{
    private PraticienServiceInterphase $praticienServise;

    public function __construct(PraticienServiceInterphase $praticienServise){
        $this->praticienServise=$praticienServise;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $praticiens=$this->praticienServise->getAllPraticiens();
        $praticiensArray=array_map( function($praticien){
            return [
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
        }, $praticiens);
        $response->getBody()->write((string)json_encode($praticiensArray));
        return $response->withHeader('content-type','application/json');
    }
}