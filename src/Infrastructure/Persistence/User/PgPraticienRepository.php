<?php
namespace App\Infrastructure\Persistence\User;

use App\core\Domain\User\User;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use App\core\Application\ports\spi\PraticienRepository;
use App\core\Domain\Praticien\Praticien;
use PhpParser\Node\Expr\Cast\Bool_;
use Ramsey\Uuid\Uuid;
use App\core\Application\ports\spi\UserRepository;


class PgPraticienRepository implements PraticienRepository {
    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

public function save(Praticien $praticien): void {
    try {
        // 1️⃣ Création ou mise à jour de l'utilisateur
        $userId = $praticien->getUser()->getId();
        $email = $praticien->getUser()->getEmail();
        $passwordHash = password_hash($praticien->getUser()->getPassword(), PASSWORD_BCRYPT);
        $role = 10;

        $stmtUser = $this->pdo->prepare("
            INSERT INTO user (id, email, password, role)
            VALUES (:id, :email, :password, :role)
            ON CONFLICT (id) DO UPDATE SET
                email = EXCLUDED.email,
                password = EXCLUDED.password,
                role = EXCLUDED.role
        ");
        $stmtUser->execute([
            'id' => $userId,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role,
        ]);

        // 2️⃣ Création ou mise à jour du praticien
        $stmtPrat = $this->pdo->prepare("
            INSERT INTO praticien (id, user_id, nom, prenom, ville, telephone, rpps_id, titre, accepte_nouveau_patient, est_organisation)
            VALUES (:id, :user_id, :nom, :prenom, :ville, :telephone, :rpps_id, :titre, :accepte_nouveau_patient, :est_organisation)
            ON CONFLICT (id) DO UPDATE SET
                nom = EXCLUDED.nom,
                prenom = EXCLUDED.prenom,
                ville = EXCLUDED.ville,
                telephone = EXCLUDED.telephone,
                rpps_id = EXCLUDED.rpps_id,
                titre = EXCLUDED.titre,
                accepte_nouveau_patient = EXCLUDED.accepte_nouveau_patient,
                est_organisation = EXCLUDED.est_organisation
        ");

        $stmtPrat->execute([
            'id' => $praticien->getId(),
            'user_id' => $userId,
            'nom' => $praticien->getNom(),
            'prenom' => $praticien->getPrenom(),
            'ville' => $praticien->getVille(),
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
$query = $this->pdo->prepare("
    SELECT
        p.*,
        u.id   AS user_id,
        u.email,
        u.role,
        u.password
    FROM praticien p
    JOIN user u ON u.id = p.user_id
");        
$query->execute();

        $praticiens = [];
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
             $user = new User(
        $row['user_id'],
        $row['email'],
        $row['role'],
        $row['password']
    );
                    $accepteNouveauPatient = isset($row['accepte_nouveau_patient']) ? (bool)$row['accepte_nouveau_patient'] : false;
            $estOrganisation = isset($row['est_organisation']) ? (bool)$row['est_organisation'] : false;

            $praticiens[] = new Praticien(
                $row['id'],
                $user,
                $row['nom'],
                $row['prenom'],
                $row['ville'],
                $row['telephone'],
                $row['rpps_id'],
                $row['titre'],
                $accepteNouveauPatient,
            $estOrganisation,
                // $row['specialite']??null,      
                // $row['motifVisite']??null,
                // $row['structure']??null,
                // $row['moyenPainement']??null,

            );
        }
        return $praticiens;
    }

    public function findPraticienOfId ($id): Praticien {
        if (!Uuid::isValid($id)) {
            throw new InvalidArgumentException('Invalid uuid string: ' . $id);
        }

        $query = $this->pdo->prepare('SELECT * FROM praticien WHERE id = :id');
        $query->execute(['id' => $id]);

        $row = $query->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            throw new \ErrorException('Praticien not found with id: ' . $id);
        }
        $userRepo = new PgUserRepository($this->pdo);
        $user = $userRepo->findById($row['user_id']);


        return new Praticien(
            $row['id'],
            $user,
            $row['nom'],
            $row['prenom'],
            $row['ville'],
            $row['telephone'],
            $row['rpps_id'],
            $row['titre'],
            (bool)($row['accepte_nouveau_patient']??false),
            (bool)($row['est_organisation']??false),
        );
    }
    public function findPraticienBySpecialite(string $specialite): array
    {
        $stmt = $this->pdo->prepare("SELECT p.id,
            p.nom,
            p.prenom,
            p.user_id,
            p.telephone,
            p.ville,
            s.libelle AS specialite, 
        FROM praticien p
        INNER JOIN specialite s ON p.specialite_id = s.id
        WHERE s.libelle = :specialite");
        $stmt->execute(['specialite' => $specialite]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findPraticienByVille(string $ville): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM praticien WHERE ville = :ville");
        $stmt->execute(['ville' => $ville]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function addIndisponibilite(string $praticienId, \DateTime $debut, \DateTime $fin, ?string $motif = null): void
{
    $stmt = $this->pdo->prepare("
        INSERT INTO indisponibilites (id, praticien_id, date_debut, date_fin, motif)
        VALUES (:id, :praticien_id, :date_debut, :date_fin, :motif)
    ");

    $stmt->execute([
        ':id' =>Uuid::uuid4()->toString(),
        ':praticien_id' => $praticienId,
        ':date_debut' => $debut->format('Y-m-d H:i:s'),
        ':date_fin' => $fin->format('Y-m-d H:i:s'),
        ':motif' => $motif
    ]);
}

public function findIndisponibilites(string $praticienId, \DateTime $dateDebut, \DateTime $dateFin): array
{
    $stmt = $this->pdo->prepare("
        SELECT * FROM indisponibilites
        WHERE praticien_id = :praticien_id
        AND date_fin >= :date_debut
        AND date_debut <= :date_fin
        ORDER BY date_debut ASC
    ");

    $stmt->execute([
        ':praticien_id' => $praticienId,
        ':date_debut' => $dateDebut->format('Y-m-d H:i:s'),
        ':date_fin' => $dateFin->format('Y-m-d H:i:s')
    ]);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
}
