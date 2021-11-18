<?php

use Oreto\Willow\App;
use Oreto\Willow\Willow;

require '../vendor/autoload.php';
$f3 = \Base::instance();
Willow::equip($f3, [App::routes()])->run();