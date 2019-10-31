<?php
require_once(__DIR__ . '/vendor/autoload.php');

$f3 = \Base::instance();

$f3->set('DEBUG', 1);
$f3->set(
    'AUTOLOAD',
    __DIR__ . '/controllers/; ' .
    __DIR__ . '/models/; ' .
    __DIR__ . '/transformers/'
);

$f3->config(__DIR__ . '/routes.ini');

$f3->run();
