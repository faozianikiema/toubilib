<?php

namespace App\core\Application\DTO;

class ProfileDTO{
    public string $id;
    public string $email;
    public string $role;

    public function __construct(string $id,string $email,string $role){
        $this->id=$id;
        $this->email=$email;
        $this->role=$role;
    }
    public function getId(){
        return $this->id;
    }
    public function getEMail(){
        return $this->email;
    }
    public function getRole(){
        return $this->role;
    }
}