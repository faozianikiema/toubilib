<?php
namespace App\core\Application\UseCase;
use App\core\Application\ports\api\RendezVousServiceInterphase;
use App\core\Application\ports\spi\RendezVousRepository;
use App\core\Domain\RendezVous\RendezVous;
use App\core\Application\DTO\InputRendezVousDTO;
use DateTime;
use Exception;
use Ramsey\Uuid\Nonstandard\Uuid;

class RendezVousService implements RendezVousServiceInterphase{
  private RendezVousRepository $repository;

    public function __construct(RendezVousRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findById(string $id): RendezVous
    {
        return $this->repository->findById($id);
    }

    public function findByPraticienOfPeriod(string $praticienID, DateTime $date_debut, DateTime $date_fin): array
    {
        return $this->repository->findByPraticienAndPeriod($praticienID, $date_debut, $date_fin);
    }
public function creerRendezVous(InputRendezVousDTO $dto): RendezVous {
    try {

        $rendezVous = new RendezVous(
            Uuid::uuid4()->toString(),
            $dto->praticienID,
            $dto->patientID,
            $dto->date_heure_debut,
            null,
            1,
            $dto->duree,
            $dto->motif_visite
        );

        $this->repository->save($rendezVous);

        return $rendezVous;

    } catch (Exception $e) {
        // tu peux loguer l'erreur ou relancer
        throw $e;
    }
}

 public function annulerRendezVous(int $idRdv):void{
    $rdv=$this->repository->findById($idRdv);
    if(!$rdv){
        throw new Exception("le rendez vous avec l'id $idRdv n'existe pas ");
    }
    $rdv->annuler();
    $this->repository->save($rdv);
 }
}


