<?php

// Uncomment this line if you must temporarily take down your site for maintenance.
// require __DIR__ . '/.maintenance.php';

$container = require __DIR__ . '/../app/bootstrap.php';
SassCompiler::run(__DIR__ .'/../scss/', __DIR__ .'/css/');
$container->getByType('Nette\Application\Application')->run();
