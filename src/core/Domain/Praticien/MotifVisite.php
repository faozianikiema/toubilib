<?php
namespace App\core\Domain\Praticien;

class MotifVisite
{
    private int $id;
    private string $libelle;
    private Praticien $praticien;
    private Specialite $specialite;


    public function __construct(string $libelle,Praticien $praticien,Specialite $specialite)
    {
        $this->libelle = $libelle;
         $this->praticien = $praticien;
         $this->specialite=$specialite;

    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getlibelle(): string
    {
        return $this->libelle;
    }
     public function getPraticien()
    {
        return $this->praticien;
    }
      public function getSpecialite()
    {
        return $this->specialite;
    }


}
