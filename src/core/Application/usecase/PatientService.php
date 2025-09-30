<?php
namespace App\core\Application\usecase;

use App\core\Application\ports\spi\PatientRepository;

class PatientService {
    private PatientRepository $repository;

    public function __construct(PatientRepository $repository) {
        $this->repository = $repository;
    }

    public function getAllPatient(): array {
        return $this->repository->findAll();
    }
}
