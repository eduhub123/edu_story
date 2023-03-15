<?php

namespace App\Services\Story2;

use App\Models\Globals\ListApp;
use App\Models\Story2\LevelSystem;
use App\Models\Story2\StoryLang;
use App\Repositories\Story2\FreeStoryRepository;
use App\Repositories\Story2\StoryLangRepository;
use App\Services\RedisService;
use Illuminate\Support\Facades\Config;

class StoryService
{

    private $freeStoryRepository;
    private $storyLangRepos;
    private $redisService;

    const KEY_REDIS_STORY_V2_LIST   = "KEY_REDIS_STORY_V2_LIST";

    public function __construct(
        FreeStoryRepository $freeStoryRepository,
        StoryLangRepository $storyLangRepos,
        RedisService $redisService
    ) {
        $this->freeStoryRepository = $freeStoryRepository;
        $this->storyLangRepos      = $storyLangRepos;
        $this->redisService        = $redisService;
    }

    public function convertVersion($version)
    {
        if ($version == 44990 || $version == 45680 || $version == 45685) {
            $version = 0;
        }
        return $version;
    }

    public function getLastVersionStory($idApp, $idLanguage)
    {
        return $this->storyLangRepos->getLastVersionStory($idApp, $idLanguage) ?? 0;
    }

    public function processDataStory($idApp, $deviceType, $idLanguage, $level, $version, $lastVersion, $isInHouse)
    {
        $keyStory  = self::KEY_REDIS_STORY_V2_LIST . "_" . $idApp . "_" . $idLanguage . "_" . $level . "_" . $version . "_" . $lastVersion;
        $listStory = $this->redisService->get($keyStory, true);
        if (!$listStory) {
            $listStory = $this->storyLangRepos->getStoriesLang($idApp, $idLanguage, $level, $version, null, null)->toArray();
            $this->redisService->set($keyStory, $listStory);
        }
        $list   = [];
        $delete = [];
        foreach ($listStory as $story) {
            $idStoryLang = $story[StoryLang::_ID_STORY_LANG];

            $status = LevelSystem::checkStatusLevelSystem($story[StoryLang::_LEVEL_SYSTEM], $story[StoryLang::_DATE_PUBLISH], $isInHouse);
            if ($status == LevelSystem::STATUS_NEW) {
                continue;
            } elseif ($status == LevelSystem::STATUS_DELETE) {
                $delete[$idStoryLang] = intval($idStoryLang);
                continue;
            }
            if ($story[StoryLang::_DATA]) {
                $dataStoryNew = json_decode($story[StoryLang::_DATA], true);
                if ($deviceType == "hd") {
                    $pathThumb = StoryLang::PATH_UPLOAD_THUMB_HD;
                    $pathZip   = StoryLang::PATH_UPLOAD_ZIP_HD;
                } else {
                    $pathThumb = StoryLang::PATH_UPLOAD_THUMB_HDR;
                    $pathZip   = StoryLang::PATH_UPLOAD_ZIP_HDR;
                }
                $dataStoryNew['quality']       = $story[StoryLang::_QUALITY_SCORE];
                $dataStoryNew['image']         = $pathThumb . "/" . $story[StoryLang::_THUMB];
                $dataStoryNew['download_link'] = $pathZip . "/" . $story[StoryLang::_PATH_ZIP_FILE];
                if ($idApp == ListApp::APP_ID_MS_VN) {
                    $dataStoryNew['quality']       = (string)$story[StoryLang::_QUALITY_SCORE];
                    $dataStoryNew['image']         = Config::get('environment.URL_DISPLAY_CDN') . $dataStoryNew['image'];
                    $dataStoryNew['download_link'] = Config::get('environment.URL_DISPLAY_CDN') . $dataStoryNew['download_link'];
                }

                $zipSizeHd  = 0;
                $zipSizeHdr = 0;
                if ($story[StoryLang::_ZIP_SIZE]) {
                    $zipSize    = json_decode($story[StoryLang::_ZIP_SIZE], true);
                    $zipSizeHd  = isset($zipSize['hd']) ? (float)$zipSize['hd'] : 0;
                    $zipSizeHdr = isset($zipSize['hdr']) ? (float)$zipSize['hdr'] : 0;
                }
                $dataStoryNew['download_link_hd_size']  = $zipSizeHd;
                $dataStoryNew['download_link_hdr_size'] = $zipSizeHdr;

                $dataStoryNew['download_link_hdr'] = Config::get('environment.URL_DISPLAY_CDN') . StoryLang::PATH_UPLOAD_ZIP_HDR . '/' . $story[StoryLang::_PATH_ZIP_FILE];
                $dataStoryNew['download_link_hd']  = Config::get('environment.URL_DISPLAY_CDN') . StoryLang::PATH_UPLOAD_ZIP_HD . '/' . $story[StoryLang::_PATH_ZIP_FILE];

                $dataStoryNew['app_id']       = (string)$story[StoryLang::_ID_APP];
                $list[$idStoryLang] = $dataStoryNew;
            }
        }
        return [$list, $delete];
    }

}
