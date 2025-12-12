<?php
namespace App\core\Application\Provider\jwt;

use App\core\Application\DTO\AuthDTO;
use App\core\Application\ports\api\auth\AuthnServiceInterface;
use App\core\Application\DTO\CredentialDTO;
use App\core\Application\Provider\AuthProviderInterface;

class JwtAuthProvider implements  AuthProviderInterface{

    private AuthnServiceInterface $authService;
    private JwTManagerInterface $jwtManager;
    public function __construct(AuthnServiceInterface $authService, JwTManagerInterface $jwtManager){
        $this->authService=$authService;
        $this->jwtManager=$jwtManager;
    }
    public function signIn(CredentialDTO $credentials):AuthDTO{
        try{
        $user=$this->authService->byCredentials($credentials);
        $accessToken=$this->jwtManager->create([
            'id'=>$user->id,
            'email'=>$user->email,
            'role'=>$user->role,
        ],JwTManagerInterface::ACCESS_TOKEN);
        $refreshToken=$this->jwtManager->create([
            'id'=>$user->id,
            'email'=>$user->email,
            'role'=>$user->role,
        ],JwTManagerInterface::REFRESH_TOKEN);
       
        $auth=new AuthDTO(
            $accessToken,
            $refreshToken,
            $user,
        );
        return $auth;

    }catch(\Exception $e){
        throw new \Exception('credentials invalides'.$e->getMessage());
    }
    }
}