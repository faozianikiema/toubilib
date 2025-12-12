<?php
namespace App\core\Application\DTO;


class CredentialDTO{
    public string $email;
    public string $password;

    public function  __construct (string $email, string $password){
        $this->email=$email;
        $this->password=$password;
    }

}