<?php
namespace App\core\Application\ports\spi;
use App\core\Domain\Praticien\Praticien;

interface PraticienRepository
{
	/**
	 * @return Praticien[]
	 */
	public function findAll(): array;

	/**
	 * @return Praticien
	 */
	public function findPraticienOfId(string $id):Praticien;
    public function save(Praticien $praticen):void;

}