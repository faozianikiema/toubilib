<?php
namespace App\core\Application\ports\api\auth;

use App\core\Application\DTO\CredentialDTO;
use App\core\Application\DTO\ProfileDTO;
use App\core\Application\DTO\Token;

interface AuthnServiceInterface{
    public function byCredentials(CredentialDTO $credentials):ProfileDTO;
    public function getSignedInUser(Token $token):ProfileDTO;
}