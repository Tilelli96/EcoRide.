<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Runtime\SymfonyRuntime;

require_once __DIR__ . '/vendor/autoload.php';

if (!isset($_ENV['APP_ENV']) && !isset($_SERVER['APP_ENV']) && file_exists(__DIR__ . '/.env')) {
    (new Dotenv())->usePutenv()->bootEnv(__DIR__ . '/.env');
}

return new SymfonyRuntime();
