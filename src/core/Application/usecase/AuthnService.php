<?php
namespace App\core\Application\useCase;
use App\core\Application\DTO\Token;
use App\core\Application\ports\api\auth\AuthnServiceInterface;
use App\core\Application\ports\spi\AuthnRepository;
use App\core\Application\ports\spi\UserRepository;
use App\core\Application\DTO\CredentialDTO;
use App\core\Application\DTO\ProfileDTO;
use App\core\Application\Provider\jwt\JwtManager;

class AuthnService implements AuthnServiceInterface{
    private AuthnRepository $authrepository;
    private UserRepository $userRepository;
    private JwtManager $jwtDecoder;
public function __construct(
    AuthnRepository $authrepository,
    UserRepository $userRepository,
    JwtManager $jwtDecoder
) {
    $this->authrepository = $authrepository;
    $this->userRepository = $userRepository;
    $this->jwtDecoder = $jwtDecoder;
}
public function byCredentials(CredentialDTO $credentials): ProfileDTO
{
    try {
        return $this->authrepository->byCredentials($credentials);
    } catch (\Exception $e) {
        throw new \Exception('credentials invalides');
    }
}
    public function getSignedInUser(Token $token):ProfileDTO {
        $payload=$this->jwtDecoder->validate($token->getToken());
        $user=$this->userRepository->findById($payload['userId']);
        return new ProfileDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getRole()
               );
    }
}