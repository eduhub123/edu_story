<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

//$app->register(Illuminate\Mail\MailServiceProvider::class);
//$app->register(\App\Providers\ZipperServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(Jenssegers\Mongodb\MongodbServiceProvider::class);
$app->register(Ixudra\Curl\CurlServiceProvider::class);
$app->register(Sentry\Laravel\ServiceProvider::class);
$app->register(Sentry\Laravel\Tracing\ServiceProvider::class);
$app->register(App\Providers\IP2LocationServiceProvider::class);
$app->register(VladimirYuldashev\LaravelQueueRabbitMQ\LaravelQueueRabbitMQServiceProvider::class);

$app->withFacades();

$app->withEloquent();


$app->configure('mail');
$app->alias('mailer', Illuminate\Contracts\Mail\Mailer::class);
$app->alias('mailer', Illuminate\Contracts\Mail\MailQueue::class);
$app->alias('Curl', Ixudra\Curl\Facades\Curl::class);
$app->alias('Sentry', Sentry\Laravel\Facade::class);

$app->configure('defineAuth');
$app->configure('constants');
$app->configure('queue');
$app->configure('device');
$app->configure('environment');
$app->configure('main');
$app->configure('apiservice');
$app->configure('defineCrm');
$app->configure('domainWeb');
$app->configure('path');
$app->configure('firebase');
$app->configure('elasticsearch');
$app->configure('exportProfileLearningDay');
$app->configure('language');
$app->configure('clervertap');
$app->configure('cdn');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

$app->middleware([
    App\Http\Middleware\Cors::class
]);

$app->routeMiddleware([
    'api.auth'           => App\Http\Middleware\ApiMiddleware::class,
    'VerifyTokenApp'     => App\Http\Middleware\VerifyTokenApp::class,
    'UserAuthentication' => App\Http\Middleware\UserAuthentication::class,
    'VerifyTokenServer'  => App\Http\Middleware\VerifyTokenServer::class
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});

$app->router->group([
    'prefix'    => 'api',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/api.php';
});

$app->router->group([
    'prefix'    => 'api/v1',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/api_v1.php';
});

return $app;
