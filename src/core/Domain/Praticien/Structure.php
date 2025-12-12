<?php
namespace App\core\Domain\Praticien;

use Ramsey\Uuid\Nonstandard\Uuid;


class Structure
{
    private Uuid $id;
    private string $nom;
    private string $adresse;
    private string $ville;
    private string $code_postal;
    private string $telephone;
    private Praticien $praticien;

    public function __construct(
        Uuid $id,
        string $nom,
        string $adresse,
        string $ville,
        string $code_postal,
        string $telephone,
        Praticien $praticien
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->code_postal = $code_postal;
        $this->telephone = $telephone;
        $this->praticien = $praticien;
    }

    public function getId():Uuid
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function getCodePostal(): string
    {
        return $this->code_postal;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }
     public function getPraticien()
    {
        return $this->praticien;
    }
}
