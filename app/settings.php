<?php
 namespace scr\config\settings;
use App\Application\Settings\Settings;
use App\core\Application\Actions\GetIdPraticienAction;
use App\core\Application\Actions\GetPatientAction;
use App\core\Application\Actions\GetRendezVousAction;
use App\core\Application\Actions\GetRendezVousByIdAction;
use App\core\Application\ports\api\PatientServiceInterface;
use App\core\Application\ports\api\RendezVousServiceInterphase;
use App\core\Application\ports\spi\PatientRepository;
use App\Core\Application\ports\spi\RendezVousRepository;
use App\core\Application\usecase\PatientService;
use App\Infrastructure\Persistence\User\PgPatientRepository;
use Psr\Container\ContainerInterface;
use App\core\Application\Actions\GetAllPraticiensAction;
use App\core\Application\ports\api\PraticienServiceInterphase;
use App\core\Application\ports\spi\PraticienRepository;
use App\core\Application\usecase\PraticienService;
use App\Infrastructure\Persistence\User\PgPraticienRepository;
use App\Infrastructure\Persistence\User\PgRendezVousRepository;
use App\core\Application\UseCase\RendezVousService;

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
        return new GetRendezVousAction($c->get(RendezVousServiceInterphase::class));},
    GetRendezVousByIdAction::class=>function(ContainerInterface $c){
        return new GetRendezVousByIdAction($c->get(RendezVousServiceInterphase::class));
    },
    GetPatientAction::class=>function(ContainerInterface $c){
        return new GetPatientAction($c->get(PatientServiceInterface::class));
    },
    // services
    PraticienServiceInterphase::class=> function (ContainerInterface $c){
        return new PraticienService($c->get(PraticienRepository::class));
    

    },
   RendezVousServiceInterphase::class=> function (ContainerInterface $c){
        return new RendezVousService($c->get(RendezVousRepository::class));},
    PatientServiceInterface::class=> function (ContainerInterface $c){
        return new PatientService($c->get(PatientRepository::class));},

    
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

    RendezVousRepository::class => fn(ContainerInterface $c)
        => new PgRendezVousRepository($c->get('toubilib.pdo')),
    PatientRepository::class=>fn(ContainerInterface $c)
       =>new PgPatientRepository($c->get('toubilib.pdo'))
];
