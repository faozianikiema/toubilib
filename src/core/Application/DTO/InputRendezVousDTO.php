<?php
namespace App\core\Application\DTO;

use DateTime;
use function DI\string;

class InputRendezVousDTO{
    public string $praticienID;
    public string $patientID;
    public DateTime $date_heure_debut;
    public int $duree;
    public string $motif_visite;
    public function __construct(
        string $praticienID,
        string $patientID,
        DateTime $date_heure_debut,
        string $motif_visite,
        int $duree
    ){
        $this->praticienID=$praticienID;
        $this->patientID=$patientID;
        $this->date_heure_debut=$date_heure_debut;
        $this->duree=$duree;
        $this->motif_visite=$motif_visite;
    }

}