<?php

use App\Main;

ini_set('display_errors', 0);

include __DIR__ . '/../vendor/autoload.php';

const __APP__ = __DIR__ . '/../';

Main::api();