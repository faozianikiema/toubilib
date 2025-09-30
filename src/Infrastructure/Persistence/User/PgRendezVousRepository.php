<?php
namespace App\Infrastructure\Persistence\User;

use App\core\Application\ports\spi\RendezVousRepository;
use App\core\Domain\RendezVous\RendezVous;
use DateTime;

use PDO;

class PgRendezVousRepository implements RendezVousRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save( RendezVous $rendezVous): void
    {
        try {
 $dateFin = $rendezVous->getDate_heure_fin() 
            ? $rendezVous->getDate_heure_fin()
            : (clone $rendezVous->getDate_heure_debut())->modify('+' . $rendezVous->getDuree() . ' minutes');            $stmt = $this->pdo->prepare("
                INSERT INTO rendez_vous (id, praticien_id, patient_id, date_heure_debut, date_heure_fin, date_creation, statut, motif_visite)
                VALUES (:id, :praticien_id, :patient_id, :date_heure_debut, :date_heure_fin, :date_creation, :statut, :motif_visite)
            ");

          $stmt->execute([
            ':id'             => $rendezVous->getId(),
            ':praticien_id'   => $rendezVous->getPraticienID(),
            ':patient_id'     => $rendezVous->getPatientID(),
            ':date_heure_debut'=> $rendezVous->getDate_heure_debut()->format('Y-m-d H:i:s'),
            ':date_heure_fin' => $rendezVous->$dateFin,
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
        $stmt = $this->pdo->prepare("SELECT * FROM rendez_vous WHERE id = :id");
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

    public function findByPraticienAndPeriod(string $praticien_id, DateTime $date_debut, DateTime $date_fin): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM rendez_vous
            WHERE praticien_id = :praticien_id
            AND date_heure_debut >= :date_debut
            AND date_heure_fin <= :date_fin
            
        ");

        $stmt->execute([
            ':praticien_id' => $praticien_id,
            ':date_debut'   => $date_debut->format('Y-m-d H:i:s'),
            ':date_fin'     => $date_fin->format('Y-m-d H:i:s'),
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rendezVousList = [];
        foreach ($rows as $row) {
            $rendezVousList[] = new RendezVous(
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

        return $rendezVousList;
    }

    // creer un rendezvous

    
}
