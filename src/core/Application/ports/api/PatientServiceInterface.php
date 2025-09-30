<?php
namespace App\core\Application\ports\api;

use App\core\Domain\Patient\Patient;

interface PatientServiceInterface
{
    /**
     * @return Patient[]
     */
    public function findById(string $id):Patient;
    public function getAllPatient(): array;
}