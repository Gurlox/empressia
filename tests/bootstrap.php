<?php

use Symfony\Component\Dotenv\Dotenv;

// executes the "php bin/console cache:clear" command
passthru(sprintf(
    'php "%s/../bin/console" cache:clear --env=test --no-warmup',
    __DIR__,
));

if (isset($_ENV['BOOTSTRAP_RESET_DATABASE']) && $_ENV['BOOTSTRAP_RESET_DATABASE'] == true) {
    echo "Resetting test database...";
    passthru('composer dev-db-reset');
    echo " Done" . PHP_EOL . PHP_EOL;
}

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
