<?php

namespace App\Services\Platform;

use App\Models\Platform\PopularSearch;
use App\Services\RedisService;
use App\Services\ServiceConnect\LessonConnectService;

class PopularSearchService
{
    private $redisService;
    private $lessonConnectService;

    public function __construct(
        RedisService $redisService,
        LessonConnectService $lessonConnectService
    ) {
        $this->redisService         = $redisService;
        $this->lessonConnectService = $lessonConnectService;
    }

    public function getPopularSearch($appId, $typeSearch)
    {
        $keyTypeSearch = implode('_', $typeSearch);

        $keyPopular  = PopularSearch::KEY_REDIS_POPULAR_SEARCH . $appId . '_' . $keyTypeSearch;
        $dataPopular = $this->redisService->get($keyPopular, true);

        if (!$dataPopular) {
            $dataPopular = $this->lessonConnectService->getPopularSearch($appId, $typeSearch);
            if ($dataPopular) {
                $this->redisService->set($keyPopular, $dataPopular);
            }
        }
        return $dataPopular;
    }
}
