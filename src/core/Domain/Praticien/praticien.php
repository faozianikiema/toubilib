<?php
namespace App\core\Domain\Praticien;

// use phpDocumentor\Reflection\Types\Boolean;

class Praticien
{
	// Add properties and methods here
    private string $id;
    private string $nom;
    private string $prenom;
    private string $ville;
    private string $email;
    private string $telephone;
    private string $rpps_id;
    private string $titre;
    private bool $accepte_nouveau_patient;
    private bool $est_organisation;
     

    public function __construct(string $id, string $nom, string $prenom, string $ville, string $email, string $telephone, string $rpps_id, string $titre, bool $accepte_nouveau_patient, bool $est_organisation){
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->rpps_id = $rpps_id;
        $this->titre = $titre;
        $this->accepte_nouveau_patient = $accepte_nouveau_patient;
        $this->est_organisation = $est_organisation;
    }
    public function getId(){
        return $this->id;
    }
    public function getNom(){
        return $this->nom;
    }
    public function getPrenom(){
        return $this->prenom;
    }
    public function getVille(){
        return $this->ville;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getTelephone(){
        return $this->telephone;
    }
    public function getRppsid(){
        return $this->rpps_id;
    }
    public function getTitre(){
        return $this->titre;
    }
    public function getAccepteNouveauPatient(){
        return $this->accepte_nouveau_patient;
    }
    public function getEstOrganisation(){
        return $this->est_organisation;
    }
}