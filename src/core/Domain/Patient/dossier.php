<?php
namespace App\core\Domain;

use Date;

class Dossier{
    private string $id;
    private string $type_document;
    private string $creer_par;
    private Date $date_creation;
    private string $fileName;

    public function __construct(string $id,string $type_document,string $creer_par,Date $date_creation, string $fileName){
        $this->id=$id;
        $this->type_document=$type_document;
        $this->creer_par=$creer_par;
        $this->date_creation=$date_creation;
        $this->fileName=$fileName;
    }
}