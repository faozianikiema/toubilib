<?php
namespace App\core\Application\ports\api\auth;

use App\core\Application\DTO\ProfileDTO;
use App\core\Domain\RendezVous\RendezVous;
interface AuthzServiceInterface{
    public function canAccessAgenda(ProfileDTO $profile,string $praticienID):bool;
    public function canAccessRendezVous(ProfileDTO $profile,RendezVous $rdv):bool;
}