<?php
namespace App\core\Application\DTO;


class AuthDTO{
    public string $access_token;
    public string $refresh_token;
    public ProfileDTO $profile;

    public function __construct(string $access_token,$refresh_token, ProfileDTO $profile){
        $this->token=$access_token;
        $this->profile=$profile;
        $this->refresh_token=$refresh_token;
    }
}