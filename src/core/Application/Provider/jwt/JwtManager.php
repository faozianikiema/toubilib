<?php

namespace App\core\Application\Provider\jwt;

use App\core\Application\Provider\jwt\JwtManagerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtManager implements JwTManagerInterface{
    private string $secret='0a0e486437eeb3b670b2869a4acc2f5bb178baa0eea9b79111b6da0896fcb394';
    private int $access_expiration_time=3600;
    private int $refresh_expiration_time=604800;
    private string $issuer="toubilib";

    public function __construct(string $secret='0a0e486437eeb3b670b2869a4acc2f5bb178baa0eea9b79111b6da0896fcb394'
    ,int $access_expiration_time=3600,int $refresh_expiration_time=604800
    ,string $issuer="toubilib"){
        $this->secret = $secret?? $_ENV['JWT_SECRET'];
        $this->access_expiration_time =$access_expiration_time?? (int) $_ENV['JWT_ACCESS_EXPIRATION'];
        $this->refresh_expiration_time = $refresh_expiration_time?? (int) $_ENV['JWT_REFRESH_EXPIRATION'];
        $this->issuer =$issuer ?? $_ENV['JWT_ISSUER'];
    }
    public function create(array $payload,string $type):string{
        if($type===JwTManagerInterface::ACCESS_TOKEN){
            $expTime=time()+$this->access_expiration_time;
        }else{
            $expTime=time()+$this->refresh_expiration_time;
        }
        $token=JWT::encode([
            'iss'=>$this->issuer,
            'iat'=>time(),
            'sub'=>$payload['id'],
            'exp'=>$expTime,
            'upr'=>$payload
        ],$this->secret, 'HS512');
        return $token;
    }
public function validate(string $token): array
{
    try {
        $decoded = JWT::decode($token, new Key($this->secret, 'HS512'));

        $userPayload = (array) $decoded->upr; // convertir en tableau

        return [
            'userId' => $decoded->sub,
            'email' => $userPayload['email'] ?? null,
            'role' => $userPayload['role'] ?? null,
        ];

    } catch (\Exception $e) {
        return [];
    }

}}