<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

$app = new \App\App($config);
$app->init();

require __DIR__ . '/../src/routes.php';
require __DIR__ . '/../src/setings.php';

$app->run();



