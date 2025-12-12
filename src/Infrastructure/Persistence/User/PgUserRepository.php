<?php
namespace App\Infrastructure\Persistence\User;
use App\core\Application\DTO\CredentialDTO;
use App\core\Application\DTO\ProfileDTO;
use App\core\Application\ports\spi\AuthnRepository;
use App\core\Application\ports\spi\UserRepository;
use App\core\Domain\User\User;
use PDO;
class PgUserRepository implements AuthnRepository, UserRepository{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo=$pdo;
    } 
    public function register(CredentialDTO $credentials, int $role):ProfileDTO{
        $stmt=$this->pdo->prepare('select count(*) from user where email=:email');
        $stmt->execute(['email'=>$credentials->email]);
        if($stmt->fetchColumn()>0){
            throw new \Exception('email existe deja');
        }
        $hashedPassword=password_hash($credentials->password,PASSWORD_BCRYPT);
        $stmt=$this->pdo->prepare(('INSERT INTO user( email, password , role) VALUES (:email , :password , :role)'));
        $stmt->execute([
            'email'=>$credentials->email,
            'password'=>$hashedPassword,
            'role'=>$role
        ]);
       $id = $this->pdo->lastInsertId();;
        return new ProfileDTO(
            (string) $id,
            $credentials->email,
            $role
        );
    }
    public function byCredentials(CredentialDTO $credentials): ProfileDTO{
        $stmt=$this->pdo->prepare('select * from user where email=:email');
        $stmt->execute(['email'=>$credentials->email]);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row){
            throw new \Exception('email introuvable');
        }
        if(!password_verify($credentials->password,$row['password'])){
            throw  new \Exception('mot de passe incorrect');
        }
        return new ProfileDTO(
            $row['id'],
            $row['email'],
            $row['role']
        );
    }

    public function byEmail(string $email): User{
        $stmt=$this->pdo->prepare('select * from user where email=:email');
        $stmt->execute(['email'=>$email]);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row){
            throw new \Exception('email introuvable');
        }
        return new User(
        $row['id'],
        $row['email'],
        $row['role'],
        $row['password']
    );
    }
 public function findById(string $id):User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        return new User(
            $row['id'],
            $row['email'],
            $row['role'],
            $row['password']
        );
    }    

}