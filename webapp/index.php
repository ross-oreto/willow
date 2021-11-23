<?php

use Oreto\F3Willow\Willow;
use Oreto\Willow\controllers\App;

require '../vendor/autoload.php';
$f3 = \Base::instance();
Willow::equip($f3, [App::routes()])->run();