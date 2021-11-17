<?php

use Oreto\Willow\App;
use Oreto\Willow\Willow;

require 'vendor/autoload.php';
$f3 = \Base::instance();
Willow::run($f3, [App::routes()]);