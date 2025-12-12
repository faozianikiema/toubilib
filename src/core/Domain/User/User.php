<?php

declare(strict_types=1);

namespace App\core\Domain\User;


class User 
{
    protected string $id;

    protected string $email;

    protected int $role;
    protected string $password;

   

    public function __construct(string $id, string $email, int $role, string $password)
    {
        $this->id = $id;
        $this->email = strtolower($email);
        $this->role = $role;
        $this->password = $password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole():int
    {
        return $this->role;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
          public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }


}
