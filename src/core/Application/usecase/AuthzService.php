<?php
namespace App\core\Application\useCase;


use App\core\Application\DTO\ProfileDTO;
use App\core\Application\ports\api\auth\AuthzServiceInterface;
use App\core\Domain\RendezVous\RendezVous;

class AuthzService implements AuthzServiceInterface{
    public function canAccessAgenda(ProfileDTO $profile, string $praticienID): bool
    {
        return $profile->getRole() === '10' || $profile->getId() === $praticienID;
    }

    /**
     * Vérifie si l'utilisateur peut accéder au détail d'un rendez-vous
     */
    public function canAccessRendezVous(ProfileDTO $profile, RendezVous $rdv): bool
    {

        if ($profile->getRole() === '10' && $profile->getId() === $rdv->getPraticienID()) {
            return true;
        }

        if ($profile->getRole() === '1' && $profile->getId() === $rdv->getPatientID()) {
            return true;
        }

        return false;
    }
}
