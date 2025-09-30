<?php
namespace App\Core\Application\useCase;

use App\Core\Application\Ports\Spi\PraticienRepository;
use App\Core\Application\Ports\Api\PraticienServiceInterphase;
use App\Core\Domain\Praticien\Praticien;

class PraticienService implements PraticienServiceInterphase {
    
    private PraticienRepository $praticienRepository;

    public function __construct(PraticienRepository $praticienRepository) {
        $this->praticienRepository = $praticienRepository;
    

    /**
     * Find a praticien by ID.
     *
     * @param string $id
     * @return Praticien|null
     */
}

    /**
     * @return Praticien[]
     */
    public function getAllPraticiens(): array {
        return $this->praticienRepository->findAll();
    }

    /**
     * Get a praticien or throw an exception if not found.
     */
    public function getPraticien(string $id): Praticien {
        $praticien = $this->praticienRepository->findPraticienOfId($id);
        if (!$praticien) {
            throw new \Exception("Praticien non trouvé avec l'ID $id");
        }
        return $praticien;
    }
}
