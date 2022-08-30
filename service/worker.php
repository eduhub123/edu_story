<?php

use Illuminate\Contracts\Console\Kernel;
use Spiral\GRPC\Server;

require_once __DIR__ . '/vendor/autoload.php';

/** 載入 Laravel 核心 */
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->boot();
//$app->make(Kernel::class)->bootstrap();

//$app->make(
//    Kernel::class
//);

//print_r($app->make(Kernel::class)->bootstrap());

//$app->run();

/** 加入 gRPC Server 物件 */
$server = $app->make(Server::class);
//$app->run();

/** 註冊想要的服務 */
//$server->registerService(\Mypackage\UserServiceInterface::class, new \App\Grpc\UserService());

$server->registerService(\Mypackage\StoryLangServiceInterface::class, new \App\Grpc\App\StoryLangController());
$server->registerService(\Mypackage\AudioBookServiceInterface::class,new \App\Grpc\App\AudioBookController());
$server->registerService(\Mypackage\WorksheetServiceInterface::class,new \App\Grpc\App\WorksheetController());
$server->registerService(\Mypackage\FreeServiceInterface::class, new \App\Grpc\App\FreeController());
/** 啟始 worker */
$worker = new  Spiral\RoadRunner\Worker(new Spiral\Goridge\StreamRelay(STDIN, STDOUT));
$server->serve($worker);
