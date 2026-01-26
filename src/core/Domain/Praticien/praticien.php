<?php
namespace App\core\Domain\Praticien;
use App\core\Domain\Praticien\Specialite;
use App\core\Domain\Praticien\Structure;
use App\core\Domain\Praticien\MotifVisite;
use App\core\Domain\Praticien\MoyenPaiement;
use App\core\Domain\User\User;


class Praticien
{
    private string $id;
    private string $nom;
    private string $prenom;
    private string $ville;
    private string $telephone;
    private ?string $rpps_id;
    private string $titre;
    private bool $accepte_nouveau_patient ;
    private bool $est_organisation;
    private ?Specialite $specialite;
    private ?Structure $structure;
    private ?MotifVisite $motifVisite;
    private ?MoyenPaiement $moyenPaiement;
    private ?User $user;


    
    public function __construct(string $id,User $user, string $nom, string $prenom, string $ville,  string $telephone, ?string $rpps_id, string $titre, bool $accepte_nouveau_patient, bool $est_organisation){
        $this->id = $id;
        $this->user = $user;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
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
    public function getSpecialite(Specialite $specialite){
        return $this->specialite;
    }
    public function getStructure(){
        return $this->structure;
    }
    public function getMotifVisite(){
        return $this->motifVisite;
    }
    public function getMoyenPaiement(){
        return $this->moyenPaiement;
    }
    public function getUser():User{return $this->user;}
    public function getEmail(): ?string
{
    return $this->user ? $this->user->getEmail() : null;
}
}