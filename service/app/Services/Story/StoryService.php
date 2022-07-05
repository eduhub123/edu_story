<?php

namespace App\Services\Story;

use App\Models\Globals\ListApp;
use App\Models\Story\FreeStory;
use App\Models\Story\StoryLang;
use App\Models\Story\StoryLevel;
use App\Repositories\Story\FreeStoryRepository;
use App\Repositories\Story\StoryLangRepository;
use App\Repositories\Story\StoryLevelRepository;
use App\Services\RedisService;
use Carbon\Carbon;

class StoryService
{

    private $freeStoryRepository;
    private $storyLevelRepository;
    private $redisService;

    const KEY_REDIS_STORY_LIST      = "KEY_REDIS_STORY_LIST";
    const KEY_REDIS_STORY_LIST_FREE = "KEY_REDIS_STORY_LIST_FREE";

    public function __construct(
        FreeStoryRepository $freeStoryRepository,
        StoryLevelRepository $storyLevelRepository,
        RedisService $redisService
    ) {
        $this->freeStoryRepository  = $freeStoryRepository;
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
            $storyLangId = $story[StoryLang::_SLANG_ID];

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
                        $fileName = $story[StoryLang::_SID] . '_' . $story[StoryLang::_LANG_ID] . '_' . $story[StoryLang::_VERSION_STORY] . '_hd.zip';
                    } else {
                        $fileName = $story[StoryLang::_SID] . '_' . $story[StoryLang::_LANG_ID] . '_' . $story[StoryLang::_VERSION_STORY] . '_hdr.zip';
                    }
                } else {
                    if ($deviceType == "hd") {
                        $fileName = $story[StoryLang::_SID] . '_' . $story[StoryLang::_LANG_ID] . '_hd.zip';
                    } else {
                        $fileName = $story[StoryLang::_SID] . '_' . $story[StoryLang::_LANG_ID] . '_hdr.zip';
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

    public function processFreeStory($version, $lastVersion)
    {
        $today = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;

        $keyStoryFree  = self::KEY_REDIS_STORY_LIST_FREE . "_" . $version . "_" . $lastVersion . "_" . $today;
        $dataStoryFree = $this->redisService->get($keyStoryFree, true);
        if (!$dataStoryFree) {
            $listFreeStory = $this->freeStoryRepository->getFreeStoryToDay()->toArray();
            $dataStoryFree = [];
            foreach ($listFreeStory as $freeStory) {
                if (isset($freeStory['story_lang_relate'][StoryLang::_LANG_ID])) {
                    $langId                   = $freeStory['story_lang_relate'][StoryLang::_LANG_ID];
                    $dataStoryFree[$langId][] = intval($freeStory[FreeStory::_SLANG_ID]);
                }
            }
            $this->redisService->set($keyStoryFree, $dataStoryFree);
        }

        return $dataStoryFree;
    }

    public function processTrialItems($profile)
    {
        $data = $this->getItemsForLess6();

        if ($profile['age'] >= 6) {
            $data = $this->getItemsForGreaterThanOrEqualTo6();
        }


        $data["free_items"] = $this->getFreeLess6();

        return $data;
    }

    private function getFreeLess6()
    {
        return [
            [
                "item_id" => 2476,
                "type" => "story"
            ],
            [
                "item_id" => 539,
                "type" => "phonics"
            ],
            [
                "item_id" => 53,
                "type" => "reading"
            ],
            [
                "item_id" => 2539,
                "type" => "story"
            ],
            [
                "item_id" => 529,
                "type" => "phonics"
            ],
            [
                "item_id" => 43,
                "type" => "reading"
            ],
            [
                "item_id" => 2526,
                "type" => "story"
            ],
            [
                "item_id" => 2455,
                "type" => "story"
            ],
            [
                "item_id" => 2476,
                "type" => "story"
            ],
            [
                "item_id" => 2467,
                "type" => "story"
            ]
        ];
    }

    private function getItemsForLess6()
    {
        return [
            "option_1_item_stories" => [
                [
                    "item_id" => 2476,
                    "type" => "story"
                ],
                [
                    "item_id" => 2467,
                    "type" => "story"
                ],
                [
                    "item_id" => 2527,
                    "type" => "story"
                ],
                [
                    "item_id" => 2539,
                    "type" => "story"
                ],
                [
                    "item_id" => 2455,
                    "type" => "story"
                ],
                [
                    "item_id" => 2479,
                    "type" => "story"
                ],
                [
                    "item_id" => 2526,
                    "type" => "story"
                ],
            ],
            "option_1_item_others" => [
                [
                    "item_id" => 2476,
                    "type" => "story"
                ],
                [
                    "item_id" => 539,
                    "type" => "phonics"
                ],
                [
                    "item_id" => 53,
                    "type" => "reading"
                ],
                [
                    "item_id" => 2539,
                    "type" => "story"
                ],
                [
                    "item_id" => 529,
                    "type" => "phonics"
                ],
                [
                    "item_id" => 43,
                    "type" => "reading"
                ],
                [
                    "item_id" => 2526,
                    "type" => "story"
                ],
            ],
            "option_2_items" => [
                [
                    "story_id" => 2476,
                    "item_id" => 539,
                    "type" => "phonics"
                ],
                [
                    "story_id" => 2467,
                    "item_id" => 53,
                    "type" => "reading"
                ],
                [
                    "story_id" => 2527,
                    "item_id" => 529,
                    "type" => "phonics"
                ],
                [
                    "story_id" => 2539,
                    "item_id" => 103,
                    "type" => "reading"
                ],
                [
                    "story_id" => 2455,
                    "item_id" => 540,
                    "type" => "phonics"
                ],
                [
                    "story_id" => 2479,
                    "item_id" => 43,
                    "type" => "reading"
                ],
                [
                    "story_id" => 2526,
                    "item_id" => 541,
                    "type" => "phonics"
                ],
            ],
        ];
    }

    private function getItemsForGreaterThanOrEqualTo6()
    {
        return [
            "option_1_item_stories" => [
                [
                    "item_id" => 2502,
                    "type" => "story"
                ],
                [
                    "item_id" => 2541,
                    "type" => "story"
                ],
                [
                    "item_id" => 2582,
                    "type" => "story"
                ],
                [
                    "item_id" => 2485,
                    "type" => "story"
                ],
                [
                    "item_id" => 2571,
                    "type" => "story"
                ],
                [
                    "item_id" => 2592,
                    "type" => "story"
                ],
                [
                    "item_id" => 2486,
                    "type" => "story"
                ],
            ],
            "option_1_item_others" => [
                [
                    "item_id" => 2502,
                    "type" => "story"
                ],
                [
                    "item_id" => 539,
                    "type" => "phonics"
                ],
                [
                    "item_id" => 165,
                    "type" => "reading"
                ],
                [
                    "item_id" => 2485,
                    "type" => "story"
                ],
                [
                    "item_id" => 529,
                    "type" => "phonics"
                ],
                [
                    "item_id" => 158,
                    "type" => "reading"
                ],
                [
                    "item_id" => 2486,
                    "type" => "story"
                ],
            ],
            "option_2_items" => [
                [
                    "story_id" => 2502,
                    "item_id" => 539,
                    "type" => "phonics"
                ],
                [
                    "story_id" => 2541,
                    "item_id" => 165,
                    "type" => "reading"
                ],
                [
                    "story_id" => 2582,
                    "item_id" => 529,
                    "type" => "phonics"
                ],
                [
                    "story_id" => 2485,
                    "item_id" => 141,
                    "type" => "reading"
                ],
                [
                    "story_id" => 2571,
                    "item_id" => 540,
                    "type" => "phonics"
                ],
                [
                    "story_id" => 2592,
                    "item_id" => 158,
                    "type" => "reading"
                ],
                [
                    "story_id" => 2486,
                    "item_id" => 541,
                    "type" => "phonics"
                ],
            ],
        ];
    }
}
