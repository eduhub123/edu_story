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

    public function __construct(
        SeriesRepository $seriesRepos,
        RedisService $redisService
    ) {
        $this->seriesRepos  = $seriesRepos;
        $this->redisService = $redisService;
    }

    public function getListSeries($idApp, $idLangDisplay, $lastVersion)
    {
        $key        = self::KEY_REDIS_LIST_SERIES_V2 . '_' . $idApp . '_' . $idLangDisplay . '_' . $lastVersion;
        $listSeries = $this->redisService->get($key, true);
        if (!$listSeries) {
            $listSeries = $this->seriesRepos->getSeries($idApp, $idLangDisplay)->keyBy(Series::_ID_SERIES)->toArray();
            $this->redisService->set($key, $listSeries, 3600);
        }
        return $listSeries;
    }
}
