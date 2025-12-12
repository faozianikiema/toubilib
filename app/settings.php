<?php
 namespace scr\config\settings;
use App\core\Application\Actions\GetIdPraticienAction;
use App\core\Application\Actions\GetPatientAction;
use App\Core\Application\Actions\GetPatientConsultationsAction;
use App\Core\Application\Actions\GetPraticienRendezVousAction;
use App\Core\Application\Actions\GetPraticienSpecialiteAction;
use App\Core\Application\Actions\GetPraticienVilleAction;
use App\core\Application\Actions\GetRendezVousAction;
use App\core\Application\Actions\GetRendezVousByIdAction;
use App\Core\Application\Actions\RegisterPatientAction;
use App\core\Application\Actions\SignInAction;
use App\Core\Application\Actions\UpdateStatusAction;
use App\core\Application\Middleware\AuthnMiddleware;
use App\core\Application\Middleware\AuthzMiddleware;
use App\core\Application\ports\api\auth\AuthnServiceInterface;
use App\core\Application\ports\api\PatientServiceInterface;
use App\core\Application\ports\api\RendezVousServiceInterphase;
use App\core\Application\ports\spi\AuthnRepository;
use App\core\Application\ports\spi\PatientRepository;
use App\Core\Application\ports\spi\RendezVousRepository;
use App\core\Application\ports\spi\UserRepository;
use App\core\Application\Provider\AuthnProvider;
use App\core\Application\Provider\AuthProviderInterface;
use App\core\Application\Provider\jwt\JwtManager;
use App\core\Application\Provider\jwt\JwTManagerInterface;
use App\core\Application\useCase\AuthnService;
use App\core\Application\useCase\AuthzService;
use App\core\Application\usecase\PatientService;
use App\Infrastructure\Persistence\User\PgArgendaRepository;
use App\Infrastructure\Persistence\User\PgPatientRepository;
use Psr\Container\ContainerInterface;
use App\core\Application\Actions\GetAllPraticiensAction;
use App\core\Application\ports\api\PraticienServiceInterphase;
use App\core\Application\ports\spi\PraticienRepository;
use App\core\Application\usecase\PraticienService;
use App\Infrastructure\Persistence\User\PgPraticienRepository;
use App\Infrastructure\Persistence\User\PgRendezVousRepository;
use App\core\Application\UseCase\RendezVousService;
use App\Infrastructure\Persistence\User\PgUserRepository;

 Return [
    // settings
    'settings'=>[
    'displayErrorDetails'=>true,
    'logs.dir'=>__DIR__.'/../logs',
    'db'=> __DIR__.'/../src/config/db.ini'
 ],

    // application 
    GetAllPraticiensAction::class=> function(ContainerInterface $c){
        return new GetAllPraticiensAction($c->get(PraticienServiceInterphase::class));
   
    },
     GetIdPraticienAction::class=> function(ContainerInterface $c){
        return new GetIdPraticienAction($c->get(PraticienServiceInterphase::class));
    },

    GetRendezVousAction::class=> function(ContainerInterface $c){
        return new GetRendezVousAction($c->get(RendezVousServiceInterphase::class),
       $c->get(AuthzService::class),
    );},
    GetRendezVousByIdAction::class=>function(ContainerInterface $c){
        return new GetRendezVousByIdAction($c->get(RendezVousServiceInterphase::class));
    
    },
    GetPatientConsultationsAction::class=>function(ContainerInterface $c){
        return new GetPatientConsultationsAction($c->get(RendezVousServiceInterphase::class));
    },
    GetPatientAction::class=>function(ContainerInterface $c){
        return new GetPatientAction($c->get(PatientServiceInterface::class));
    },
    RegisterPatientAction::class=>function(ContainerInterface $c){
        return new RegisterPatientAction($c->get(PatientServiceInterface::class));
    },
    
    SignInAction::class=> function(ContainerInterface $c) {
        return new SignInAction( $c->get(AuthProviderInterface::class));
    },
    GetPraticienSpecialiteAction::class=> function(ContainerInterface $c){
        return new GetPraticienSpecialiteAction($c->get(PraticienServiceInterphase::class));
    },
        GetPraticienVilleAction::class=> function(ContainerInterface $c){
        return new GetPraticienVilleAction($c->get(PraticienServiceInterphase::class));
    },
    UpdateStatusAction::class=> function(ContainerInterface $c){
        return new UpdateStatusAction($c->get(RendezVousServiceInterphase::class));
    },
    GetPraticienRendezVousAction::class=> function(ContainerInterface $c){
        return new GetPraticienRendezVousAction($c->get(RendezVousServiceInterphase::class));
    },


    // services
    PraticienServiceInterphase::class=> function (ContainerInterface $c){
        return new PraticienService($c->get(PraticienRepository::class));
    

    },
   RendezVousServiceInterphase::class=> function (ContainerInterface $c){
        return new RendezVousService($c->get(RendezVousRepository::class));},
    PatientServiceInterface::class=> function (ContainerInterface $c){
        return new PatientService($c->get(PatientRepository::class));},

    AuthnServiceInterface::class=>function(ContainerInterface $c){
         return new AuthnService(
            $c->get(AuthnRepository::class),
          $c->get(UserRepository::class),
           $c->get(JwtManager::class));},
    
    
                 //   provider
JwTManagerInterface::class => function ($c) {
        $secret = $_ENV['JWT_SECRET']?? '0a0e486437eeb3b670b2869a4acc2f5bb178baa0eea9b79111b6da0896fcb394';
        $issuer = $_ENV['JWT_ISSUER'] ?? 'toubilib';

        $jwtManager = new JwtManager($secret,(int)$_ENV['JWT_ACCESS_EXPIRATION'] ?? 3600,(int) $_ENV['JWT_REFRESH_EXPIRATION']?? 86400,$issuer);

        return $jwtManager;
    },


    AuthProviderInterface::class => function ($c) {
        return new AuthnProvider(
            $c->get(AuthNServiceInterface::class),
            $c->get(JwTManagerInterface::class)
        );
    },
      // Middleware d’authentification
    AuthnMiddleware::class => function(ContainerInterface $c) {
        return new AuthnMiddleware(
            $c->get(JwtManager::class),
            $c->get(UserRepository::class)
        );
    },

    // Middleware d’autorisation
    AuthzMiddleware::class => function(ContainerInterface $c) {
        return new AuthzMiddleware(
            $c->get(AuthzService::class),
            $c->get(RendezVousRepository::class),
            $c->get(JwtManager::class)
        );
    },



    //  infractucture
    'toubilib.pdo'=> function(ContainerInterface $c){
        $settings=$c->get('settings');
        $config=parse_ini_file($settings['db']);
        $dsn="{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
        $praticien=$config['username'];
        $password=$config['password'];

        return new \PDO($dsn,$praticien,$password,[\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION]);
    },
    PraticienRepository::class=> fn(ContainerInterface $c)
                              => new PgPraticienRepository($c->get('toubilib.pdo')),

    RendezVousRepository::class=>fn(ContainerInterface $c)
        =>new PgArgendaRepository($c->get(id:'toubilib.pdo')),
    PatientRepository::class=>fn(ContainerInterface $c)
       =>new PgPatientRepository($c->get('toubilib.pdo')),
   AuthnRepository::class=>fn(ContainerInterface $c)
         =>new PgUserRepository($c->get('toubilib.pdo')),
    UserRepository::class=>fn(ContainerInterface $c)
         =>new PgUserRepository($c->get('toubilib.pdo'))
];
