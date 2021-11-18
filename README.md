# Willow 
Willow is a simple php web app that leverages the amazing fat free framework in a practical way.
- https://fatfreeframework.com
- Willow is derived from the word Willowy which can mean graceful or slender https://www.thesaurus.com/browse/willowy
- Also, the movie is awesome.

### Requirements
- PHP 8.x
- Nodejs (optional - for build minification only)

### Features
- Fast and light
- Targets PHP 8.0+ (very little modifications needed to use PHP 7.x)
- Uses Monolog
- Ideal folder structure for security (only webapp is exposed)
- Environment aware
- Ready to run on PHP built-in web server and Apache
- Configurable
- Fluent route building API

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
Willow::run($f3, [App::routes()]);
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