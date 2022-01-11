<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    $string = filter_input(INPUT_SERVER, 'HTTP_NOTE', FILTER_DEFAULT) ?? null;
    return (env('APP_NAME') . " new service 2021 ". $string);
});

