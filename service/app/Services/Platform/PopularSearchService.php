<?php

use App\Models\Platform\PopularSearch;
use App\Repositories\Platform\PopularSearchRepository;
use App\Services\RedisService;

class PopularSearchService
{
    private $redisService;
    private $popularSearchRepository;

    public function __construct(
        RedisService $redisService,
        PopularSearchRepository $popularSearchRepository
    ) {
        $this->redisService            = $redisService;
        $this->popularSearchRepository = $popularSearchRepository;
    }

    public function getPopularSearch($typeSearch, $appId, $os, $lang)
    {
        $keyTypeSearch = implode('_', $typeSearch);

        $keyPopular  = PopularSearch::KEY_REDIS_POPULAR_SEARCH_DATA . $appId . '_' . $keyTypeSearch . '_' . $os . '_' . $lang;
        $dataPopular = $this->redisService->get($keyPopular, true);

        if (!$dataPopular) {
            $dataPopular = $this->popularSearchRepository->getPopularSearch($appId, $typeSearch)->toArray();
            foreach ($dataPopular as $key => $value) {
                $dataPopular[$key]['thumb'] = config('environment.URL_DISPLAY_CDN'). $value['thumb'];
            }
            $this->redisService->set($keyPopular, $dataPopular);
        }
        return $dataPopular;
    }
}
