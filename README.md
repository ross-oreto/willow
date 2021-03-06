# Willow 
Willow is a simple php web app that leverages the amazing fat free framework in a practical way.
- https://fatfreeframework.com
- Willow is derived from the word Willowy which can mean graceful or slender https://www.thesaurus.com/browse/willowy
- Also, the movie is awesome.

### Requirements
- PHP 8.x
- composer
- Nodejs (optional - for build minification only)

### Features
- Fast and light
- Targets PHP 8.0+ (very little modifications needed to use PHP 7.x)
- Uses Monolog (the most widely accepted PHP logging lib)
- Ideal folder structure for security (only webapp is exposed)
- Environment aware
- Ready to run on PHP built-in web server and Apache
- Configurable
- Fluent route building API
- Comes with PHPUnit test suite which uses http guzzle for integration tests 
- i18n/internationalization ready

### Installation 
```
git clone https://github.com/ross-oreto/willow
cd willow
composer install 
```

### Run locally using run.sh script
```bash
./run.sh
```
### Run locally using run.sh script with mode/port
```bash
./run.sh stage
./run.sh stage 8080
./run.sh dev 8081
````

### Run locally with php
```bash
cd webapp
php -S localhost:8001 server.php
```

### Run locally with php in stage/prod mode 
Willow looks for the environment variable "mode". Default is 'dev'
```bash
cd webapp
mode=stage php -S localhost:8001 server.php
mode=prod php -S localhost:8001 server.php
```

### DEBUG Environment variable
    0 : suppresses logs of the stack trace.
    1 : logs files & lines.
    2 : logs classes & functions as well.
    3 : logs detailed infos of the objects as well.

### Debug with a debugger 
Setup xdebug php.ini
```
  [xdebug]
  zend_extension="<path to xdebug extension>"
  xdebug.mode=debug
  xdebug.client_host=127.0.0.1
  xdebug.client_port="<the port (9003 by default) to which Xdebug connects>"
```
- PHP Built in server run config
- doc root: webapp
- working dir: webapp
- interpreter args: server.php
- xdebug helper browser extension
  - Use PHPSTORM IDE key for any Jetbrains product


### Running unit and integration tests
```
composer test
```

### A note on Node and minification
- Firstly this is not a node application
- Node and webpack are used for bundling assets only, not needed for local/dev mode.
- Fat-Free does have a web plugin to help minify assets, however it is buggy (Nice try though)
- Using Web::instance()->minify() to minify the latest jquery.js, resulted in invalid javascript.
- Unfortunately the only reliable way to minify and bundle assets is to leverage node, especially if we consider things like es6 modules and importing.
```
npm run build
```

### The Willow Class
Initializing and running Willow is straightforward
```
Willow::equip($f3, [App::routes()])->run();
```
This call will likely be from index.php.
1. The first argument is an instance of fat-free Base class.
2. The second argument is an array of Routes which will be the result of a static method extended from the Willow class. In other words App extends Willow.

### The Router API
``` 
Routes::create(self::class)   1. The controller class name
           ->GET("home", "/") 2. The name of the route (home) and the uri pattern '/'
           ->handler('index') 3. The controller function name aka action
           ->build();         4. Build and return the Routes object.
```
It's also fluent:
```
return Routes::create(self::class)
    ->GET("list-items", "/")->handler('index')
    ->POST("save-item", "/")->handler('save')
    ->GET("get-item", "/@id")->handler('get')
    ->PUT("update-item", "/@id")->handler('update')
    ->DELETE("delete-item", "/@id")->handler('delete')
    ->build();
```

### logs
- By default log files are kept in /logs/app.log
- Configurable in config.ini 
```
LOGS="../logs/"
logName=
```
Access the Willow logger statically or within the controller object using:
```
Willow::getLogger()->info($x);  1) global logger
$this->log->info($x);           2) protected controller logger 
```

### i18n
Lookup language message from src/dict/en.ini using 
```
$message = Willow::dict('name');
```
In templates access dictionary using DICT preface:
```
{{ @DICT.404.message }}
```