<?php
namespace App\core\Application\ports\api;
use App\core\Domain\User\User;

interface UserServiceInterface
{
    /**
     * @return User[]
     */
    public function getAllUsers():array;
}