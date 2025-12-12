<?php
namespace App\core\Domain\Praticien;

class MoyenPaiement
{
    private int $id;
    private string $libelle;
    private Praticien $praticien;

    public function __construct(string $libelle,Praticien $praticien)
    {
        $this->libelle = $libelle;
        $this->praticien=$praticien;
    }


    public function getId(): int
    {
        return $this->id;
    }

    
    public function getPraticien()
    {
        return $this->praticien;
    }


}
