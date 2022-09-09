<?php

namespace App\Services\Story2;

use App\Models\LangDisplay;
use App\Models\Story\DescriptionLevel;
use App\Models\Story2\Level;
use App\Models\Story2\Translate;
use App\Repositories\Story\DescriptionLevelRepository;
use App\Repositories\Story2\LevelRepository;
use App\Repositories\Story2\TranslateRepository;
use App\Services\RedisService;

class LevelService
{
    private $levelRepos;
    private $descriptionLevelRepos;
    private $translateRepos;
    private $redisService;

    const KEY_REDIS_LEVEL_DESCRIPTION = 'KEY_REDIS_LEVEL_DESCRIPTION';

    public function __construct(
        LevelRepository $levelRepos,
        DescriptionLevelRepository $descriptionLevelRepos,
        TranslateRepository $translateRepos,
        RedisService $redisService
    ) {
        $this->levelRepos            = $levelRepos;
        $this->descriptionLevelRepos = $descriptionLevelRepos;
        $this->translateRepos        = $translateRepos;
        $this->redisService          = $redisService;
    }

    public function getListLevel($idApp)
    {
        $levels        = $this->levelRepos->getLevelsByIdApp($idApp)->toArray();
        $translateKeys = [];
        foreach ($levels as $level) {
            $translateKeys[] = $level[Level::_KEY_NAME];
            $translateKeys[] = $level[Level::_KEY_DESCRIPTION];
        }
        $translates     = $this->translateRepos->getTranslate($translateKeys)->toArray();
        $dataTranslates = [];
        foreach ($translates as $translate) {
            $dataTranslates[$translate[Translate::_KEY]][$translate[Translate::_ID_LANG_DISPLAY]] = $translate[Translate::_VALUE];
        }
        $levelIds   = [];
        $dataLevels = [];
        foreach ($levels as $level) {
            $levelId          = (int)$level[Level::_LEVEL];
            $item['id']       = $levelId;
            $item['grade_id'] = (int)$level[Level::_ID_GRADE];
            foreach (LangDisplay::LIST_LANG_DISPLAY as $idLangDisplay) {
                $item['name']                 = $dataTranslates[$level[Level::_KEY_NAME]][$idLangDisplay] ?? "";
                $item['des']                  = $dataTranslates[$level[Level::_KEY_DESCRIPTION]][$idLangDisplay] ?? "";
                $dataLevels[$idLangDisplay][] = $item;
            }
            $levelIds[$levelId] = $levelId;
        }
        return [$levelIds, $dataLevels];
    }

    public function getDescriptionLevels()
    {
        $key               = self::KEY_REDIS_LEVEL_DESCRIPTION;
        $descriptionLevels = $this->redisService->get($key, true);
        if (!$descriptionLevels) {
            $descriptionLevels = $this->descriptionLevelRepos->getDescription()->toArray();
            $this->redisService->set($key, $descriptionLevels, 3600);
        }
        return $descriptionLevels;
    }

    public function processDataDescriptionLevel()
    {
        $descriptionLevels = $this->getDescriptionLevels();
        $description       = [];
        foreach ($descriptionLevels as $descriptionLevel) {
            $description[] = [
                'order'           => intval($descriptionLevel[DescriptionLevel::_LEVEL_ORDER]),
                'description'     => $descriptionLevel[DescriptionLevel::_DESCRIPTION],
                'lang_display_id' => intval($descriptionLevel[DescriptionLevel::_LANG_DISPLAY_ID])
            ];
        }
        return $description;
    }

}
