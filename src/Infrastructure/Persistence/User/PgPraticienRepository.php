<?php
namespace App\Infrastructure\Persistence\User;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use App\core\Application\ports\spi\PraticienRepository;
use App\core\Domain\Praticien\Praticien;
use Ramsey\Uuid\Uuid;

class PgPraticienRepository implements PraticienRepository {
    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function save(Praticien $praticien): void {
        try {
            $query = $this->pdo->prepare(
                'INSERT INTO praticien (uuid, nom, prenom, ville, email, telephone, rpps_id, titre, accepte_nouveau_patient, est_organisation)
                 VALUES (:uuid, :nom, :prenom, :ville, :email, :telephone, :rpps_id, :titre, :accepte_nouveau_patient, :est_organisation)
                 ON CONFLICT (uuid) DO UPDATE SET
                    nom = EXCLUDED.nom,
                    prenom = EXCLUDED.prenom,
                    ville = EXCLUDED.ville,
                    email = EXCLUDED.email,
                    telephone = EXCLUDED.telephone,
                    rpps_id = EXCLUDED.rpps_id,
                    titre = EXCLUDED.titre,
                    accepte_nouveau_patient = EXCLUDED.accepte_nouveau_patient,
                    est_organisation = EXCLUDED.est_organisation'
            );

            $query->execute([
                'uuid' => $praticien->getId(),
                'nom' => $praticien->getNom(),
                'prenom' => $praticien->getPrenom(),
                'ville' => $praticien->getVille(),
                'email' => $praticien->getEmail(),
                'telephone' => $praticien->getTelephone(),
                'rpps_id' => $praticien->getRppsid(),
                'titre' => $praticien->getTitre(),
                'accepte_nouveau_patient' => $praticien->getAccepteNouveauPatient(),
                'est_organisation' => $praticien->getEstOrganisation(),
            ]);
        } catch (\PDOException $e) {
            throw new \RuntimeException('Database error: ' . $e->getMessage());
        }
    }

    public function findAll(): array {
        $query = $this->pdo->prepare('SELECT * FROM praticien');
        $query->execute();

        $praticiens = [];
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
            $praticiens[] = new Praticien(
                $row['uuid'],
                $row['nom'],
                $row['prenom'],
                $row['ville'],
                $row['email'],
                $row['telephone'],
                $row['rpps_id'],
                $row['titre'],
                (bool)$row['accepte_nouveau_patient'],
                (bool)$row['est_organisation']
            );
        }
        return $praticiens;
    }

    public function findPraticienOfId ($id): Praticien {
        if (!Uuid::isValid($id)) {
            throw new InvalidArgumentException('Invalid uuid string: ' . $id);
        }

        $query = $this->pdo->prepare('SELECT * FROM praticien WHERE uuid = :uuid');
        $query->execute(['uuid' => $id]);

        $row = $query->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            throw new \ErrorException('Praticien not found with id: ' . $id);
        }

        return new Praticien(
            $row['uuid'],
            $row['nom'],
            $row['prenom'],
            $row['ville'],
            $row['email'],
            $row['telephone'],
            $row['rpps_id'],
            $row['titre'],
            (bool)$row['accepte_nouveau_patient'],
            (bool)$row['est_organisation']
        );
    }
}
