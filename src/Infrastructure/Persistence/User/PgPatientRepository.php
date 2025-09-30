<?php
namespace App\Infrastructure\Persistence\User;

use App\core\Application\ports\spi\PatientRepository;
use App\core\Domain\Patient\Patient;

use DateTime;
use PDO;
use Ramsey\Uuid\Uuid;


class PgPatientRepository implements PatientRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findPatientOfId(string $id):Patient
    {
        $stmt = $this->pdo->prepare("SELECT * FROM patient WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

       

        return new Patient(
            $row['uuid'],
            $row['nom'],
            $row['prenom'],
            $row['date_naissance'],
            $row['adresse'],
            $row['ville'],
            $row['code_postal'],
            $row['email'],
            $row['telephone']
        );
    }

    public function save(Patient $patient): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO patient (uuid, nom, prenom,date_naissance,adresse,ville,code_postal email, telephone)
            VALUES (:uuid, :nom, :prenom,:date_naissance,:adresse,:ville,:code_postal, :email, :telephone)
            ON CONFLICT (id) DO UPDATE
            SET nom = :nom, prenom = :prenom,date_naissance=:date_naissance,adresse=:adresse,ville=:ville,code_postal=:code_postal, email = :email, telephone = :telephone
        ");

        $stmt->execute([
            'uuid' => $patient->getId(),
            'nom' => $patient->getNom(),
            'prenom' => $patient->getPrenom(),
            'date_naissance'=>$patient->getDateNaissance()->format('Y-m-d'),
            'adresse'=>$patient->getAdresse(),
            'ville'=>$patient->getVille(),
            'code_postal'=>$patient->getCode_postal(),
            'email' => $patient->getEmail(),
            'telephone' => $patient->getTelephone()
        ]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM patient");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $patients = [];
        foreach ($rows as $row) {
            $patients[] = new Patient(
                $row['uuid'],
                $row['nom'],
                $row['prenom'],
                new DateTime($row['date_naissance']),
                $row['adresse'],
                $row['ville'],
                $row['code_postal'],
                $row['email'],
                $row['telephone']
            );
        }

        return $patients;
    }
}
