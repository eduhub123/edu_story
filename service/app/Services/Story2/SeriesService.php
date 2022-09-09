<?php

namespace App\Services\Story2;

use App\Models\Story2\Series;
use App\Repositories\Story2\SeriesRepository;
use App\Services\RedisService;

class SeriesService
{

    private $seriesRepos;
    private $redisService;

    const KEY_REDIS_LIST_SERIES_V2 = 'KEY_REDIS_LIST_SERIES_V2';
    const KEY_REDIS_LIST_SERIES_V2_ALL_LANG_DISPLAY = 'KEY_REDIS_LIST_SERIES_V2_ALL_LANG_DISPLAY';

    public function __construct(
        SeriesRepository $seriesRepos,
        RedisService $redisService
    ) {
        $this->seriesRepos    = $seriesRepos;
        $this->redisService   = $redisService;
    }

    public function getListSeriesByLangDisplay($idApp, $idLangDisplay, $lastVersion)
    {
        $key        = self::KEY_REDIS_LIST_SERIES_V2 . '_' . $idApp . '_' . $idLangDisplay . '_' . $lastVersion;
        $listSeries = $this->redisService->get($key, true);
        if (!$listSeries) {
            $listSeries = $this->seriesRepos->getListSeriesByLangDisplay($idApp, $idLangDisplay)
                ->keyBy(Series::_ID_SERIES)->toArray();
            $this->redisService->set($key, $listSeries, 3600);
        }
        return $listSeries;
    }

    public function getListSeries($idApp, $lastVersion)
    {
        $key        = self::KEY_REDIS_LIST_SERIES_V2_ALL_LANG_DISPLAY . '_' . $idApp . '_' . $lastVersion;
        $listSeries = $this->redisService->get($key, true);
        if (!$listSeries) {
            $listSeries = $this->seriesRepos->getListSeries($idApp)->toArray();
            $this->redisService->set($key, $listSeries, 3600);
        }
        return $listSeries;
    }
}
