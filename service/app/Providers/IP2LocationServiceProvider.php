<?php

namespace App\Providers;

use App\Services\AliasLoader;
use Ip2location\IP2LocationLaravel\IP2LocationLaravelServiceProvider;

class IP2LocationServiceProvider extends IP2LocationLaravelServiceProvider
{

    public function register()
    {
        //Dynamically add IP2LocationLaravel alias
        AliasLoader::getInstance()->alias('IP2LocationLaravel', 'Ip2location\IP2LocationLaravel\Facade\IP2LocationLaravel');

        $dir = str_replace("app/Providers","",__DIR__);
        $config =  $dir.'/vendor/ip2location/ip2location-laravel/src/Config/ip2locationlaravel.php';

        $this->publishes([
            $config => config_path('ip2locationlaravel.php'),
        ], 'config');

        $this->mergeConfigFrom( $config, 'ip2locationlaravel');

        // $this->app['ip2locationlaravel'] = $this->app->share(function($app){
        // return new IP2LocationLaravel;
        // });
    }

}
