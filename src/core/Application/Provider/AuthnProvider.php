<?php
namespace App\core\Application\Provider;

use App\core\Application\DTO\AuthDTO;
use App\core\Application\DTO\CredentialDTO;
use App\core\Application\DTO\ProfileDTO;
use App\core\Application\ports\api\auth\AuthnServiceInterface;
use App\core\Application\Provider\jwt\JwTManagerInterface;


class AuthnProvider implements  AuthProviderInterface{
        private AuthnServiceInterface $authnService;
    private  $jwtManager;

    public function __construct(
        AuthnServiceInterface $authnService, 
        JwTManagerInterface $jwtManager
    ) {
        $this->authnService = $authnService;
        $this->jwtManager = $jwtManager;
    }


    public function signIn(CredentialDTO $credential): AuthDTO {
        $profile = $this->authnService->byCredentials($credential);
        
        $access_token = $this->jwtManager->create([
            'id' => $profile->id,
            'email' => $profile->email,
        ], JwtManagerInterface::ACCESS_TOKEN);
        
        $refresh_token = $this->jwtManager->create([
            'id' => $profile->id,
            'email' => $profile->email,
        ], JwtManagerInterface::REFRESH_TOKEN);
        
        return new AuthDTO($access_token, $refresh_token, $profile);
    }

    public function getSignedInUser(string $token): ProfileDTO {
        $payload = $this->jwtManager->validate($token);
        
        return new ProfileDto(
            $payload['id'], 
            $payload['nom'], 
            $payload['email']
        );
    }

    public function refresh(string $refreshToken): AuthDto {
        $payload = $this->jwtManager->validate($refreshToken);
        
        $profile = new ProfileDto(
            $payload['id'],
            $payload['nom'],
            $payload['email']
        );
        
        $access_token = $this->jwtManager->create([
            'id' => $profile->id,
            'email' => $profile->email,
        ], JwtManagerInterface::ACCESS_TOKEN);
        
        $new_refresh_token = $this->jwtManager->create([
            'id' => $profile->id,
            'email' => $profile->email,
        ], JwtManagerInterface::REFRESH_TOKEN);
        
        return new AuthDTO($access_token, $new_refresh_token, $profile);
    }

    
}