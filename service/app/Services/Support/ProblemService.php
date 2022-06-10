<?php

namespace App\Services\Support;

use App\Services\RedisService;
use App\Services\ServiceConnect\SupportConnectService;

class ProblemService
{
    private $redisService;
    private $supportConnectService;

    const  KEY_REDIS_LIST_APP_PROBLEM = 'KEY_REDIS_LIST_APP_PROBLEM';

    public function __construct(
        RedisService $redisService,
        SupportConnectService $supportConnectService
    ) {
        $this->redisService          = $redisService;
        $this->supportConnectService = $supportConnectService;
    }

    public function getListAppProblem()
    {
        $key            = self::KEY_REDIS_LIST_APP_PROBLEM;
        $listAppProblem = $this->redisService->get($key, true);
        if (!$listAppProblem) {
            $listAppProblem = $this->supportConnectService->getListAppProblem();
            $this->redisService->set($key, $listAppProblem, 3600);
        }
        return $listAppProblem;
    }
}
