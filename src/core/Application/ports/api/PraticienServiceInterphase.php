<?php
namespace App\core\Application\ports\api;
use App\core\Domain\Praticien\Praticien;

interface PraticienServiceInterphase{
    /**
     * @return Praticien[]
     */
    public function getAllPraticiens(): array;
}
