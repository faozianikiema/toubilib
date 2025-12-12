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
            $row['id'],
            $row['nom'],
            $row['prenom'],
            $row['date_naissance'],
            $row['adresse'],
            $row['ville'],
            $row['code_postal'],
            $row['telephone'],
            $row['user.email'],

        );
    }

public function save(Patient $patient): void
{
    $user = $patient->getUser();
    $userId = $user->getId();
    $email = $user->getEmail();
    $passwordHash = $user->getPassword(); 
    $role = 1; 

    $this->pdo->beginTransaction();
    try {
        $stmtUser = $this->pdo->prepare("
            INSERT INTO user (id, email, password, role)
            VALUES (:id, :email, :password, :role)
        ");
        $stmtUser->execute([
            'id' => $userId,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role,
        ]);


        $stmtPatient = $this->pdo->prepare("
            INSERT INTO patient (id, user_id, nom, prenom, date_naissance, adresse, ville, code_postal, telephone)
            VALUES (:id, :user_id, :nom, :prenom, :date_naissance, :adresse, :ville, :code_postal, :telephone)
            ON DUPLICATE KEY UPDATE
                nom = :nom,
                prenom = :prenom,
                date_naissance = :date_naissance,
                adresse = :adresse,
                ville = :ville,
                code_postal = :code_postal,
                telephone = :telephone
        ");
        $stmtPatient->execute([
            'id' => $patient->getId(),
            'user_id' => $userId,
            'nom' => $patient->getNom(),
            'prenom' => $patient->getPrenom(),
            'date_naissance' => $patient->getDateNaissance()->format('Y-m-d'),
            'adresse' => $patient->getAdresse(),
            'ville' => $patient->getVille(),
            'code_postal' => $patient->getCode_postal(),
            'telephone' => $patient->getTelephone(),
        ]);

        $this->pdo->commit();
    } catch (\Exception $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM patient");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $patients = [];
        foreach ($rows as $row) {
            $patients[] = new Patient(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                new DateTime($row['date_naissance']),
                $row['adresse'],
                $row['ville'],
                $row['code_postal'],
                $row['telephone'],
                $row['user.email'],
            );
        }

        return $patients;
    }
}
