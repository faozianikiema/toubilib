<?php
namespace App\core\Application\Provider\jwt;

use function DI\string;

interface JwTManagerInterface{
     public const ACCESS_TOKEN = 'access_token';
    public const REFRESH_TOKEN = 'refresh_token';
    
    public function create(array $payload,string $type):string;
    public function validate(string $token): array;
}