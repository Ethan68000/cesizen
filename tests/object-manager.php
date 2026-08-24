<?php

require dirname(__DIR__).'/vendor/autoload.php';

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

$container = $kernel->getContainer();

/** @var \Doctrine\Persistence\ManagerRegistry $doctrine */
$doctrine = $container->get('doctrine');

return $doctrine->getManager();