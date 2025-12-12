<?php

namespace App\core\Domain\Praticien;
use App\core\Domain\Praticien\Praticien;

class Specialite
{
    public function __construct(
        private int $id,
        private string $libelle,
        private string $description,
        private Praticien $praticien,
        private MotifVisite $motifVisite
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }
    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPraticien(): Praticien{
        return $this->praticien;
    }
    public function getMotifVisite():MotifVisite{
        return $this->motifVisite;
    }
}