<?php

if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
    // executes the "php bin/console cache:clear" command
    passthru(sprintf(
        'php "%s/../bin/console" cache:clear --env=%s --no-warmup',
        __DIR__,
        $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV']
    ));
}


if (isset($_ENV['BOOTSTRAP_SETUP_DATABASE']) && $_ENV['BOOTSTRAP_SETUP_DATABASE'] == true) {
    $filename = sprintf('%s/test.db', realpath(__DIR__ . '/../var'));
    $_ENV['DATABASE_URL'] = sprintf('sqlite:///%s', $filename);

    unlink($filename);
    passthru(sprintf(
        'DATABASE_URL="%s" php "%s/../bin/console" doctrine:schema:update --env=%s --force',
        $_ENV['DATABASE_URL'],
        __DIR__,
        $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV']
    ));
}

require __DIR__.'/../vendor/autoload.php';
