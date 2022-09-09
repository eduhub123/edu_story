<?php

namespace App\Services\Story2;

use App\Models\Story2\LangDisplay;
use App\Repositories\Story2\LangDisplayRepository;
use App\Services\RedisService;

class LangDisplayService
{
    private $langDisplayRepos;
    private $redisService;

    const KEY_REDIS_LIST_LANG_DISPLAY = 'KEY_REDIS_LIST_LANG_DISPLAY';

    public function __construct(
        LangDisplayRepository $langDisplayRepos,
        RedisService $redisService
    ) {
        $this->langDisplayRepos = $langDisplayRepos;
        $this->redisService     = $redisService;
    }

    public function getLangDisplays()
    {
        $key          = self::KEY_REDIS_LIST_LANG_DISPLAY;
        $langDisplays = $this->redisService->get($key, true);
        if (!$langDisplays) {
            $langDisplays = $this->langDisplayRepos->getLangDisplays()->keyBy(LangDisplay::_ID_LANG_DISPLAY)->toArray();
            $this->redisService->set($key, $langDisplays);
        }
        return $langDisplays;
    }

    public function processDataLangDisplays(){
        $langDisplays = $this->getLangDisplays();
        $data =  [];
        foreach ($langDisplays as $idLangDisplay => $langDisplay){
            $data[$idLangDisplay] = [
                'id' => $idLangDisplay,
                'name' => $langDisplay[LangDisplay::_LANGUAGE],
            ];
        }
        return $data;
    }
}
