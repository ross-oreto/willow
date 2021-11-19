<?php

use Oreto\Willow\Test\TestApp;
use Oreto\Willow\Willow;

require '../vendor/autoload.php';
$f3 = \Base::instance();
Willow::equip($f3, [TestApp::routes()])->run();