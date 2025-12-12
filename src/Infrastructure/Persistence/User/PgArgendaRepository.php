<?php

namespace App\Infrastructure\Persistence\User;
use App\core\Application\ports\spi\RendezVousRepository;
use App\core\Domain\RendezVous\RendezVous;
use DateTime;
use PDO;

class PgArgendaRepository implements RendezVousRepository{
      private PDO $pdo;

      public function __construct(PDO $pdo){
        $this->pdo=$pdo;
      }
        public function findByPraticienAndPeriod(string $praticienID,DateTime $date_debut,DateTime $date_fin): array{
            if(!$date_debut){
                $date_debut= new DateTime("2025-09-28 10:00:00");
            }
            if(!$date_fin){
                $date_fin=(clone $date_debut)->setTime(23, 59 ,59);
            }
        $stmt=$this->pdo->prepare(
            "Select r.id , r.date_heure_debut , r.duree, r.motif_visite,r.statut,
                    p.id As patient_id ,p.nom As patient_nom, p.prenom
                    
            FROM rendezvous r
            INNER JOIN patient p ON r.patient_id=p.id
            WHERE r.praticien_id=:praticienID
                  AND r.date_heure_debut BETWEEN :date_debut AND :date_fin
                  ORDER BY r.date_heure_debut ASC"

        );
        $stmt->execute([
            ':praticienID'=>$praticienID,
            ':date_debut'=>$date_debut->format('Y-m-d H:i:s'),
            ':date_fin'=>$date_fin->format('Y-m-d H:i:s')
        ]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rdvs=[];
    $agenda = array_map(fn($row) => [
            $row['id'],
            $praticienID,
            $row['patient_id'],
            new \DateTime($row['date_heure_debut']),
            $date_fin,
            $row['statut'],
            $row['duree'],
            $row['motif_visite']
   ],$rdvs);
    

    return $agenda;

        }
    public function save( RendezVous $rendezVous): void
    {
        try {
 $dateFin = $rendezVous->getDate_heure_fin() 
            ? $rendezVous->getDate_heure_fin()
            : (clone $rendezVous->getDate_heure_debut())->modify('+' . $rendezVous->getDuree() . ' minutes');   
                 $stmt = $this->pdo->prepare("
                INSERT INTO rendezvous (id, praticien_id, patient_id, date_heure_debut, date_heure_fin, date_creation, statut,duree, motif_visite)
                VALUES (:id, :praticien_id, :patient_id, :date_heure_debut, :date_heure_fin, :date_creation, :statut,:duree, :motif_visite)
            ");

          $stmt->execute([
            ':id'             => $rendezVous->getId(),
            ':praticien_id'   => $rendezVous->getPraticienID(),
            ':patient_id'     => $rendezVous->getPatientID(),
            ':date_heure_debut'=> $rendezVous->getDate_heure_debut()->format('Y-m-d H:i:s'),
            ':date_heure_fin' => $dateFin->format("Y-m-d H:i:s"),
            ':date_creation'  => $rendezVous->getDate_creation()->format('Y-m-d H:i:s'),
            ':statut'         => $rendezVous->getStatut(),
            ':duree'          => $rendezVous->getDuree(),
            ':motif_visite'   => $rendezVous->getMotif_visite()
        ]);
        } catch (\PDOException $e) {
            throw new \RuntimeException("Database error: " . $e->getMessage());
        }
    }

    public function findById(string $id):RendezVous
    {
        $stmt = $this->pdo->prepare("SELECT * FROM rendezvous WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new \RuntimeException("RendezVous $id non trouvé");
        }

        return new RendezVous(
            $row['id'],
            $row['praticien_id'],
            $row['patient_id'],
            new DateTime($row['date_heure_debut']),
            new DateTime($row['date_heure_fin']),
            $row['statut'],
            $row['duree'],
            $row['motif_visite']
        );
    }
        public function majStatusRdv(string $rdvId, string $status): bool{
        $stmt=$this->pdo->prepare('UPDATE rendezvous SET statut=:statut WHERE id=:id');
        $stmt->execute([
            ':statut'=>$status,
            ':id'=>$rdvId
        ]);
        return $stmt->rowCount() >0;
    }
public function findByPraticien(string $praticienID): array
{
$stmt = $this->pdo->prepare("
    SELECT 
        r.id,
        r.date_heure_debut,
        r.date_heure_fin,
        r.statut,
        r.patient_id,
        p.nom AS patient_nom,
        p.prenom AS patient_prenom
    FROM rendezvous r
    JOIN patient p ON r.patient_id = p.id  
    WHERE r.praticien_id = :praticienID
    ORDER BY r.date_heure_debut DESC
");

    $stmt->execute(['praticienID' => $praticienID]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}
public function findByPatient(string $patientID): array
{
    $stmt = $this->pdo->prepare("
        SELECT 
            r.id,
            r.praticien_id,
            r.date_heure_debut,
            r.date_heure_fin,
            r.statut,
            r.motif_visite,
            p.nom AS patient_nom,
            p.prenom AS patient_prenom
        FROM rendezvous r
        JOIN patient p ON r.patient_id = p.id
        WHERE r.patient_id = :patientID
        ORDER BY r.date_heure_debut DESC
    ");

    $stmt->execute(['patientID' => $patientID]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Optionnel : formater les données pour l'API
    $consultations = array_map(function($row) {
        return [
            'id' => $row['id'],
            'praticien_id' => $row['praticien_id'],
            'date_heure_debut' => $row['date_heure_debut'],
            'date_heure_fin' => $row['date_heure_fin'],
            'statut' => $row['statut'],
            'motif_visite' => $row['motif_visite'],
            'patient' => [
                'nom' => $row['patient_nom'],
                'prenom' => $row['patient_prenom']
            ]
        ];
    }, $rows);

    return $consultations;
}




}
