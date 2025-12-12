<?php
namespace App\core\Application\UseCase;
use App\core\Application\ports\api\RendezVousServiceInterphase;
use App\core\Application\ports\spi\PraticienRepository;
use App\core\Application\ports\spi\RendezVousRepository;
use App\core\Domain\RendezVous\RendezVous;
use App\core\Application\DTO\InputRendezVousDTO;
use DateTime;
use Exception;
use Ramsey\Uuid\Nonstandard\Uuid;

class RendezVousService implements RendezVousServiceInterphase{
  private RendezVousRepository $repository;
  private PraticienRepository $praticienRepository;

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

 public function annulerRendezVous(string $idRdv):void{
    $rdv=$this->repository->findById($idRdv);
    if(!$rdv){
        throw new Exception("le rendez vous avec l'id $idRdv n'existe pas ");
    }
    $rdv->annuler();
    $this->repository->save($rdv);
 }
  public function getAgendaPraticien(string $praticienID,DateTime $date_debut , DateTime    $date_fin = null): array
    {
        $rdvs = $this->repository->findByPraticienAndPeriod($praticienID, $date_debut, $date_fin);

   $agenda = array_map(fn($rdv) => [
    'id' => $rdv->getId(),
    'date-debut' => $rdv->getDate_heure_debut()->format('H:i'),
    'heure' => $rdv->getDate_heure_fin()->format('H:i'),
    'duree' => $rdv->getDuree(),
    'motif' => $rdv->getMotif_visite(),
    'statut' => $rdv->getStatut() ? 0 : 1,
    'patient' => [
        'id' => $rdv->getPatientID(),
        'lien' => '/patients/' . $rdv->getPatientID(),
    ]
], $rdvs);
return $agenda;

    }
    public function updateStatus(string $rdvId, string $status): bool{
        return $this->repository->majStatusRdv($rdvId, $status);
    }
    public function GetPratienRendezVous(string $praticienID): array{
        return $this->repository->findByPraticien($praticienID);
        }
    public function GetRendezVousPatient(string $patientID): array{
        return $this->repository->findByPatient($patientID);
    }
       
    public function createRendezVous(RendezVous $rdv): void
   {
    $indispos = $this->praticienRepository->findIndisponibilites(
       $rdv->getPraticienID(),
        $rdv->getDate_heure_debut(),
        $rdv->getDate_heure_fin()
    );

    if (!empty($indispos)) {
        throw new \RuntimeException("Le praticien est indisponible à cette période.");
    }

    $this->repository->save($rdv);
}

}


