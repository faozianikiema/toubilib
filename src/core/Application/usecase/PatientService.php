<?php
namespace App\core\Application\usecase;

use App\core\Application\ports\spi\PatientRepository;
use App\core\Domain\Patient\Patient;
use Ramsey\Uuid\Nonstandard\Uuid;


class PatientService {
    private PatientRepository $repository;

    public function __construct(PatientRepository $repository) {
        $this->repository = $repository;
    }

    public function getAllPatient(): array {
        return $this->repository->findAll();
    }
    public function registerPatient($patient): Patient
    {$user = $patient->getUser();
    
    if (!$user || !$user->getPassword()) {
        throw new \InvalidArgumentException("Le patient doit avoir un utilisateur avec un mot de passe.");
    }

        $this->repository->save($patient);

        return $patient;
    }
    public function findById(string $id):Patient{
        return $this->repository->findPatientOfId($id);
    }
}
