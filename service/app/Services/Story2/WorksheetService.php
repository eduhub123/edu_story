<?php

namespace App\Services\Story2;

use App\Models\Story2\Level;
use App\Models\Story2\LevelSystem;
use App\Models\Story2\StoryLang;
use App\Models\Story2\StoryLangCategory;
use App\Models\Story2\Worksheet;
use App\Models\Story2\WorksheetAssignLesson;
use App\Repositories\Story2\WorksheetRepository;
use App\Repositories\Story2\StoryLangRepository;
use App\Services\RedisService;
use App\Services\ServiceConnect\LessonConnectService;

class WorksheetService
{
    private $worksheetRepos;
    private $storyLangRepos;
    private $lessonConnectService;
    private $redisService;

    const KEY_REDIS_WORKSHEET_V2 = 'KEY_REDIS_WORKSHEET_V2';

    public function __construct(
        WorksheetRepository $worksheetRepos,
        StoryLangRepository $storyLangRepos,
        LessonConnectService $lessonConnectService,
        RedisService $redisService
    ) {
        $this->worksheetRepos       = $worksheetRepos;
        $this->storyLangRepos       = $storyLangRepos;
        $this->lessonConnectService = $lessonConnectService;
        $this->redisService         = $redisService;
    }

    public function getLastVersionWorksheet($idApp)
    {
        return $this->worksheetRepos->getLastVersionWorksheet($idApp);
    }

    public function getDataWorksheet($idApp, $idLanguage, $version, $deviceType, $inHouse = false, $storiesLang =  [], $dataPhonic = [])
    {
        $key           = self::KEY_REDIS_WORKSHEET_V2 . '_' . $idApp . '_' . $idLanguage . '_' . $version . '_' . $deviceType . '_' . $inHouse;
        $dataWorksheet = $this->redisService->get($key, true);
        if (!$dataWorksheet) {
            $worksheets      = $this->worksheetRepos->getWorksheets($idApp, $version, $inHouse)->groupBy(Worksheet::_TYPE);
            $worksheetStory  = $worksheets[Worksheet::TYPE_STORY] ?? [];
            $worksheetPhonic = $worksheets[Worksheet::TYPE_PHONIC] ?? [];
            $listRemove          = [];
            $dataWorksheetStory  = $this->getLessonStory($worksheetStory, $listRemove, $idApp, $idLanguage, $deviceType, $inHouse, $storiesLang);
            $dataWorksheetPhonic = $this->getLessonPhonic($worksheetPhonic, $listRemove, $dataPhonic);
            $dataWorksheet['workSheetStory']  = array_values($dataWorksheetStory);
            $dataWorksheet['workSheetPhonic'] = array_values($dataWorksheetPhonic);
            $dataWorksheet['delete']          = $listRemove;
            $this->redisService->set($key, $dataWorksheet);
        }
        return $dataWorksheet;
    }

    private function getLessonPhonic($worksheets, &$listRemove, $dataPhonic = [])
    {
        if (!$worksheets) {
            return [];
        }

        $worksheets = $worksheets->keyBy(WorksheetAssignLesson::_LESSON_ID)->toArray();
        if (count($dataPhonic) == 0) {
            $dataPhonic = $this->lessonConnectService->getListLessonByIds(array_keys($worksheets));
        }

        foreach ($worksheets as $idLesson => &$worksheet) {
            if (!isset($dataPhonic[$idLesson])) {
                $listRemove[] = (int)$worksheet[Worksheet::_ID];
                unset($worksheets[$idLesson]);
            }
            $valuePhonic = $dataPhonic[$idLesson];
            if (isset($valuePhonic['is_deleted'])) {
                $listRemove[] = (int)$worksheet[Worksheet::_ID];
                unset($worksheets[$idLesson]);
                continue;
            }
            $worksheet['thumb']       = $valuePhonic['path_thumb'] ?? "";
            $worksheet['name_lesson'] = $valuePhonic['title'];
            $worksheet['level']       = [];
            $worksheet['category']    = [$valuePhonic['game_category_id']];
        }
        return $worksheets;
    }

    private function getLessonStory($worksheets, &$listRemove, $idApp, $idLanguage, $deviceType, $isInHouse, $storiesLang = [])
    {
        if (!$worksheets) {
            return [];
        }

        $worksheets = $worksheets->keyBy(WorksheetAssignLesson::_LESSON_ID)->toArray();
        if (count($storiesLang) == 0) {
            $storiesLang = $this->storyLangRepos->getListById(array_keys($worksheets), $idApp, $idLanguage)->keyBy(StoryLang::_ID_STORY_LANG)->toArray();
            foreach ($storiesLang as $idStoryLang => &$storyLang) {
                $status = LevelSystem::checkStatusLevelSystem($storyLang[StoryLang::_LEVEL_SYSTEM], $storyLang[StoryLang::_DATE_PUBLISH], $isInHouse);
                if ($status == LevelSystem::STATUS_NEW) {
                    unset($storiesLang[$idStoryLang]);
                    continue;
                } elseif ($status == LevelSystem::STATUS_DELETE) {
                    unset($storiesLang[$idStoryLang]);
                    continue;
                }
                if ($deviceType == "hd") {
                    $pathThumb = StoryLang::PATH_UPLOAD_THUMB_HD;
                } else {
                    $pathThumb = StoryLang::PATH_UPLOAD_THUMB_HDR;
                }
                $storiesLang[$idStoryLang]['image'] = $pathThumb . '/' . $storyLang[StoryLang::_THUMB];
                $levels             = [];
                foreach ($storyLang['story_lang_level'] as $story_lang_level) {
                    if (isset($story_lang_level['level']) && $story_lang_level['level']) {
                        $levels[] = $story_lang_level['level'][Level::_LEVEL];
                    }
                }
                $storiesLang[$idStoryLang]['level']    = array_unique($levels);
                $storiesLang[$idStoryLang]['category'] = array_unique(array_column($storyLang['story_lang_category'], StoryLangCategory::_ID_CATEGORY));
            }
        }
        foreach ($worksheets as $idStoryLang => &$worksheet) {
            $idWorksheet = (int)$worksheet[Worksheet::_ID];
            if (!isset($storiesLang[$idStoryLang])) {
                $listRemove[] = $idWorksheet;
                unset($worksheets[$idStoryLang]);
                continue;
            }

            $worksheet['thumb']       = $storiesLang[$idStoryLang]['image'];
            $worksheet['name_lesson'] = $storiesLang[$idStoryLang]['name'];
            $worksheet['level']       = $storiesLang[$idStoryLang]['level'];
            $worksheet['category']    = $storiesLang[$idStoryLang]['category'] ?? [];
        }
        return $worksheets;
    }

}
