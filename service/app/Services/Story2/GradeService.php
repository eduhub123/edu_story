<?php

namespace App\Services\Story2;

use App\Models\LangDisplay;
use App\Models\Story2\Grade;
use App\Models\Story2\Translate;
use App\Repositories\Story2\GradeRepository;
use App\Repositories\Story2\TranslateRepository;

class GradeService
{
    private $gradeRepos;
    private $translateRepos;

    public function __construct(
        GradeRepository $gradeRepos,
        TranslateRepository $translateRepos
    ) {
        $this->gradeRepos     = $gradeRepos;
        $this->translateRepos = $translateRepos;
    }

    public function getListGrade($idApp)
    {
        $grades        = $this->gradeRepos->getGradesByIdApp($idApp)->toArray();
        $translateKeys = [];
        foreach ($grades as $grade) {
            $translateKeys[] = $grade[Grade::_KEY_NAME];
            $translateKeys[] = $grade[Grade::_KEY_DESCRIPTION];
        }
        $translates     = $this->translateRepos->getTranslate($translateKeys)->toArray();
        $dataTranslates = [];
        foreach ($translates as $translate) {
            $dataTranslates[$translate[Translate::_KEY]][$translate[Translate::_ID_LANG_DISPLAY]] = $translate[Translate::_VALUE];
        }

        $data = [];
        foreach ($grades as $grade) {
            $item['id']    = (int)$grade[Grade::_ID_GRADE];
            $item['order'] = (int)$grade[Grade::_GROUP];

            foreach (LangDisplay::LIST_LANG_DISPLAY as $idLangDisplay) {
                $item['name']           = $dataTranslates[$grade[Grade::_KEY_NAME]][$idLangDisplay] ?? "";
                $item['des']            = $dataTranslates[$grade[Grade::_KEY_DESCRIPTION]][$idLangDisplay] ?? "";
                $data[$idLangDisplay][] = $item;
            }
        }
        return $data;
    }
}
