<?php
namespace App\core\Application\ports\api;
use App\core\Domain\Praticien\Praticien;

interface PraticienServiceInterphase{
    /**
     * @return Praticien[]
     */
    public function getAllPraticiens(): array;
    public function getPraticien(string $id):Praticien;
    public function GetPraticienBySpecialite(string $specialite):array;
    public function GetPraticienByVille(string $ville):array;
}
