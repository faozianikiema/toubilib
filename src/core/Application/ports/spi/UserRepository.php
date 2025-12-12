<?php

namespace App\core\Application\ports\spi;

use App\core\Domain\User\User;
use function DI\string;

interface UserRepository{
    public function findById(string $id):User;
}