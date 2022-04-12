<?php

namespace App\Services\Story2;

use App\Models\Globals\ListApp;
use App\Models\Story2\StoryLang;
use App\Models\Story2\StoryLevel;
use App\Repositories\Story2\StoryLevelRepository;
use App\Services\RedisService;

class StoryService
{
    private $storyLevelRepository;
    private $redisService;

    const KEY_REDIS_STORY_LIST      = "KEY_REDIS_STORY_LIST";
    const KEY_REDIS_STORY_LIST_FREE = "KEY_REDIS_STORY_LIST_FREE";

    public function __construct(
        StoryLevelRepository $storyLevelRepository,
        RedisService $redisService
    ) {
        $this->storyLevelRepository = $storyLevelRepository;
        $this->redisService         = $redisService;
    }

    public function processDataStory($appId, $deviceType, $langId, $level, $version, $lastVersion, $isNetworkEarlyStart)
    {
        $keyLevelStory = self::KEY_REDIS_STORY_LIST . "_" . $langId . "_" . $level . "_" . $version . "_" . $lastVersion . '_' . $isNetworkEarlyStart;
        $listStory     = $this->redisService->get($keyLevelStory, true);

        if (!$listStory) {
            $listStory = $this->storyLevelRepository->getAllLevel($langId, $level, $version, null, null, $isNetworkEarlyStart)->toArray();
            $this->redisService->set($keyLevelStory, $listStory);
        }
        $list   = [];
        $delete = [];
        foreach ($listStory as $i => $story) {
            $storyLangId = $story[StoryLang::_ID_STORY_LANG];

            if ($story[StoryLang::_DELETE] == StoryLevel::IS_DELETE) {
                $delete[$storyLangId] = intval($storyLangId);
                continue;
            }
            if ($story[StoryLang::_DATA]) {
                $dataStoryNew = json_decode($story[StoryLang::_DATA], true);

                if ($deviceType == "hd") {
                    $dataStoryNew['image'] = 'images/thumbnail/hd/' . $story[StoryLang::_ICON];
                } else {
                    $dataStoryNew['image'] = 'images/thumbnail/hdr/' . $story[StoryLang::_ICON];
                }

                if ($appId == ListApp::APP_ID_MS_VN) {
                    $dataStoryNew['quality'] = (string)$story[StoryLang::_QUALITY_SCORE];
                } else {
                    $dataStoryNew['quality'] = $story[StoryLang::_QUALITY_SCORE];
                }

                if ($story[StoryLang::_DATE_PUBLISH] > 1615546200 && $appId == ListApp::APP_ID_MS_EN) {
                    if ($deviceType == "hd") {
                        $fileName = $story[StoryLang::_ID_STORIES] . '_' . $story[StoryLang::_ID_LANGUAGES] . '_' . $story[StoryLang::_VERSION_STORY] . '_hd.zip';
                    } else {
                        $fileName = $story[StoryLang::_ID_STORIES] . '_' . $story[StoryLang::_ID_LANGUAGES] . '_' . $story[StoryLang::_VERSION_STORY] . '_hdr.zip';
                    }
                } else {
                    if ($deviceType == "hd") {
                        $fileName = $story[StoryLang::_ID_STORIES] . '_' . $story[StoryLang::_ID_LANGUAGES] . '_hd.zip';
                    } else {
                        $fileName = $story[StoryLang::_ID_STORIES] . '_' . $story[StoryLang::_ID_LANGUAGES] . '_hdr.zip';
                    }
                }

                $dataStoryNew['download_link'] = 'zip/' . $fileName;

                if ($story[StoryLang::_ZIP_SIZE]) {
                    $zipSize = json_decode($story[StoryLang::_ZIP_SIZE], true);
                }
                $dataStoryNew['download_link_hd_size']  = isset($zipSize['hd']) ? (float)$zipSize['hd'] : 0;
                $dataStoryNew['download_link_hdr_size'] = isset($zipSize['hdr']) ? (float)$zipSize['hdr'] : 0;

                $list[$i] = $dataStoryNew;
            }
        }

        $data['story']  = array_values($list);
        $data['delete'] = array_values($delete);
        return $data;
    }
}