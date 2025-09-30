<?php
namespace App\core\Application\ports\spi;
use App\core\Domain\Patient\Patient;

interface PatientRepository{
    /**
	 * @return Patient[]
	 */
	public function findAll(): array;

	/**
	 * @return Patient
	 */
	public function findPatientOfId(string $id):Patient;
    public function save(Patient $patient):void;
}