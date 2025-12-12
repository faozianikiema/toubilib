<?php
 namespace App\core\Domain\Patient;

 use App\core\Domain\User\User;
 use Date;
 use DateTime;
 use function DI\string;

 class Patient {
    private string $id;
    private string $nom;
    private string $prenom;
    private DateTime $date_naissance;
    private string $adresse;
    private string $ville;
    private string $code_postal;
    private string $telephone;
    private User $user;
    public function __construct(
        string $id,
        string $nom,
        string $prenom,
        DateTime $date_naissance,
        string $adresse,
        string $ville,
        string $code_postal,
        string $telephone,
        User $user
        
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->date_naissance=$date_naissance;
        $this->adresse=$adresse;
        $this->ville=$ville;
        $this->code_postal=$code_postal;
        $this->telephone = $telephone;
        $this->user=$user;
    }

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getDateNaissance(){return $this->date_naissance;}
    public function getAdresse(){return $this->adresse;}
    public function getVille(){return $this->ville;}
    public function getCode_postal(){return $this->code_postal;}
    public function getTelephone(): string { return $this->telephone; }
    public function getUser():User { return $this->user;}
}


 