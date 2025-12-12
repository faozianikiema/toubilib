<?php 
namespace App\Core\Application\ports\spi;
use App\core\Domain\RendezVous\RendezVous;
use DateTime;

interface RendezVousRepository{
    /**
     * @return RendezVous[]
     */

    public function findByPraticienAndPeriod(string $praticienID,DateTime $date_debut,DateTime $date_fin):array
    /**
     * @return RendezVous
     */;
    public function findById(string $id):RendezVous;
    public function save(RendezVous $rendezVous):void;
    public function majStatusRdv(string $rdvId, string $status): bool;
    public function findByPraticien(string $praticienID):array;
    public function findByPatient(string $patientID):array;

}
