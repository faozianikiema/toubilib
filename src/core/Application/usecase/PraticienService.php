<?php
namespace App\core\Application\usecase;
use App\core\Application\ports\spi\PraticienRepository;
use App\core\Application\ports\api\PraticienServiceInterphase;
use App\core\Domain\Praticien\Praticien;

class PraticienService implements PraticienServiceInterphase {
    private PraticienRepository $praticienRepository;
    public function __construct(PraticienRepository $praticienRepository){
        $this->praticienRepository=$praticienRepository;
    }
    /**
    * @return Praticien[]
    */
    public function getAllPraticiens(): array
    {
        return $this->praticienRepository->findAll();
    }
}