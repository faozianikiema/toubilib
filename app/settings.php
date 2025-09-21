<?php
 namespace scr\config\settings;
use App\Application\Settings\Settings;
use Psr\Container\ContainerInterface;
use App\core\Application\Actions\GetAllPraticiensAction;
use App\core\Application\ports\api\PraticienServiceInterphase;
use App\core\Application\ports\spi\PraticienRepository;
use App\core\Application\usecase\PraticienService;
use App\Infrastructure\Persistence\User\PgPraticienRepository;

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
    // services
    PraticienServiceInterphase::class=> function (ContainerInterface $c){
        return new PraticienService($c->get(PraticienRepository::class));

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
                              => new PgPraticienRepository($c->get('toubilib.pdo'))

];
