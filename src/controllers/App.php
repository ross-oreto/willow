<?php

namespace Oreto\Willow\controllers;


use Oreto\F3Willow\Routing\Routes;
use Oreto\F3Willow\Willow;

class App extends Willow {
    static function routes(): Routes {
       return Routes::create(self::class)
           ->GET("home", "/")->handler('index')
           ->build();
    }
}