<?php

namespace App\core\Application\ports\spi;

use App\core\Application\DTO\CredentialDTO;
use App\core\Application\DTO\ProfileDTO;
use App\core\Domain\User\User;
interface AuthnRepository{
    public function register(CredentialDTO $credentials, int $role):ProfileDTO;
    public function byCredentials(CredentialDTO $credentials):ProfileDTO;
    public function byEmail(string $email):User;
}