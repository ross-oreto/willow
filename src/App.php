<?php

namespace Oreto\Willow;

use Oreto\Willow\Routing\Routes;

class App extends Willow {
    static function routes(): Routes {
       return Routes::create(self::class)
           ->GET("home", "/")->handler('index')
           ->build();
    }
}