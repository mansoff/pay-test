<?php
/**
 * čia as atskirčiau web/index.php nuo app/console.php,
 * bet reikalavime buvo index php
 */
if (!empty($argv)) {
    $container = require __DIR__ . '/../app/preloader.php';

    /** @var \Symfony\Component\Console\Application $console */
    $console = $container->get('console');
    $console->run();

} else {
    echo 'Sorry, this is console app';
}
