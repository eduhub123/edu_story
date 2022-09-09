<?php

namespace App\Services\Story2;

use App\Models\Story2\PopularSearch;
use App\Repositories\Story2\PopularSearchRepository;
use App\Services\RedisService;
use App\Services\ServiceConnect\LessonConnectService;
use Illuminate\Support\Facades\Config;

class PopularSearchService
{
    private $redisService;
    private $popularSearchRepos;
    private $lessonConnectService;

    const KEY_REDIS_POPULAR_SEARCH    = 'KEY_REDIS_POPULAR_SEARCH_DATA';
    const KEY_REDIS_POPULAR_SEARCH_V2 = 'KEY_REDIS_POPULAR_SEARCH_V2';

    public function __construct(
        RedisService $redisService,
        PopularSearchRepository $popularSearchRepos,
        LessonConnectService $lessonConnectService
    ) {
        $this->redisService         = $redisService;
        $this->popularSearchRepos   = $popularSearchRepos;
        $this->lessonConnectService = $lessonConnectService;
    }

    public function getPopularSearchV2($idApp, $types)
    {
        $keyTypes = implode('_', $types);
        $key      = self::KEY_REDIS_POPULAR_SEARCH_V2 . '_' . $idApp . '_' . $keyTypes;
        $data     = $this->redisService->get($key, true);
        if (!$data) {
            $data = $this->popularSearchRepos->getPopularSearch($idApp, $types)->toArray();
            $this->redisService->set($key, $data, 3600);
        }
        return $data;
    }

    public function getPopularSearchV2MV($idApp, $types)
    {
        $popularSearches = $this->getPopularSearchV2($idApp, $types);
        foreach ($popularSearches as $key => $popularSearch) {
            $popularSearches[$key][PopularSearch::_THUMB] = Config::get('environment.URL_DISPLAY_CDN') . $popularSearch[PopularSearch::_THUMB];
        }
        return $popularSearches;
    }

    public function getPopularSearch($appId, $typeSearch)
    {
        $keyTypeSearch = implode('_', $typeSearch);

        $keyPopular  = self::KEY_REDIS_POPULAR_SEARCH . '_' . $appId . '_' . $keyTypeSearch;
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
