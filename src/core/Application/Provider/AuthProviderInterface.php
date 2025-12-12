<?php

namespace App\core\Application\Provider;

use App\core\Application\DTO\AuthDTO;
use App\core\Application\DTO\CredentialDTO;

interface AuthProviderInterface{
     /**
    * @return AuthDTO[]
    */

    public function signIn(CredentialDTO $credential):AuthDTO;
}