<?php

namespace App\Services\Story2;

use App\Models\LangDisplay;
use App\Models\Story2\Level;
use App\Models\Story2\Translate;
use App\Repositories\Story2\LevelRepository;
use App\Repositories\Story2\TranslateRepository;

class LevelService
{
    private $levelRepos;
    private $translateRepos;

    public function __construct(
        LevelRepository $levelRepos,
        TranslateRepository $translateRepos
    ) {
        $this->levelRepos     = $levelRepos;
        $this->translateRepos = $translateRepos;
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

}
