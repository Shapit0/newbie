<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../src/main.php';
require __DIR__.'/../src/vacancy.php';
require __DIR__.'/../src/admin.php';

$app->run();

