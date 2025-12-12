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
	public function findPraticienBySpecialite(string $specialite):array;
	public function findPraticienByVille(string $ville):array;
    public function findIndisponibilites(string $praticienId, \DateTime $dateDebut, \DateTime $dateFin): array;


}