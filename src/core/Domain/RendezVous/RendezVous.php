<?php
namespace App\core\Domain\RendezVous;

use DateTime;
use Exception;

class RendezVous{
    private string $id;
    private string $praticienID;
    private string $patientID;
    private DateTime $date_heure_debut;
    private ?DateTime $date_heure_fin;
    private DateTime $date_creation;
    private int $statut;
    private int $duree;
    private string $motif_visite;
    private string $etat;

    public function __construct(string $id,string $praticienID,string $patientID,DateTime $date_heure_debut,?DateTime $date_heure_fin,int $statut,int $duree,string $motif_visite,string $etat='prevu'){
        $this->id=$id;
        $this->praticienID=$praticienID;
        $this->patientID=$patientID;
        $this->date_heure_debut=$date_heure_debut;
        $this->date_heure_fin=$date_heure_fin;
        $this->date_creation=new DateTime();
        $this->statut=$statut;
        $this->duree=$duree;
        $this->motif_visite=$motif_visite;
        $this->etat=$etat;

    }

    public function getId(){
        return $this->id;
    }

    public function getPraticienID(){
        return $this->praticienID;
    }
    public function getPatientID(){
        return $this->patientID;
    }
    public function getDate_heure_debut(){
        return $this->date_heure_debut;
    }
    public function getDate_heure_fin(){
        return $this->date_heure_fin;
    }
    public function getDate_creation(){
        return $this->date_creation;
    }
    public function getStatut(){
        return $this->statut;
    }
    public function getDuree(){
        return $this->duree;
    }
    public function getMotif_visite(){
        return $this->motif_visite;
    }
    

    function annuler(){
        /**
         * @throws Exception si l’annulation est impossible
         */
        if($this->etat==='annuler'){
            throw new Exception("Le rendez-vous est déjà annulé.");
        }

        if($this->date_heure_debut<=new DateTime()){
            throw new Exception("impossilbe d'annuler le rendez vous");
        }
        $this->etat = "ANNULE";
    }

}
