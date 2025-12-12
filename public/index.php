<?php

declare(strict_types=1);
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';
// env 
$dotenv=Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();



/* application boostrap */
$appli = require_once __DIR__ . '/../src/config/bootstrap.php';
$hash = password_hash("Alice123!", PASSWORD_BCRYPT);
var_dump($hash);

$appli->run();

