<?php
namespace App\core\Application\ports\api;
use App\core\Application\DTO\InputRendezVousDTO;
use App\core\Domain\RendezVous\RendezVous;
use DateTime;

interface RendezVousServiceInterphase {
    /**
    * @return RendezVous[]
    */

    public function findByPraticienOfPeriod(string $praticienID,DateTime $date_debut,DateTime $date_fin):Array;
    public function findById(String $id):RendezVous;
    public function creerRendezVous(InputRendezVousDTO $dto):RendezVous;
     /**
     * Annule un rendez-vous par son identifiant.
     *
     * @param int|string $idRdv Identifiant du rendez-vous
     * @throws \Exception si le rendez-vous n’existe pas ou ne peut pas être annulé
     */
    public function  annulerRendezVous(string $idRdv):void;
    
    
}
