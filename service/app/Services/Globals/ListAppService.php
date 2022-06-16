<?php

namespace App\Services\Globals;

use App\Services\RedisService;
use App\Services\ServiceConnect\AppConnectService;

class ListAppService
{
    private $redisService;
    private $appConnectService;

    const  KEY_REDIS_LIST_APP = 'KEY_REDIS_LIST_APP';

    public function __construct(
        RedisService $redisService,
        AppConnectService $appConnectService
    ) {
        $this->redisService      = $redisService;
        $this->appConnectService = $appConnectService;
    }

    public function getListApp()
    {
        $key     = self::KEY_REDIS_LIST_APP;
        $listApp = $this->redisService->get($key, true);
        if (!$listApp) {
            $listApp = $this->appConnectService->getListApp();
            $this->redisService->set($key, $listApp, 3600);
        }
        return $listApp;
    }

    public function getAppByIdApp($idApp)
    {
        $listApp = $this->getListApp();
        return $listApp[$idApp] ?? [];
    }
}
