<?php

namespace App\Services\Platform;

use App\Models\Globals\ListApp;
use App\Models\Platform\VersionApiLoad;
use App\Services\RedisService;
use App\Services\ServiceConnect\LessonConnectService;

class VersionService
{
    private $lessonConnectService;
    private $redisService;

    const TYPE_STORIES_ACTIVITES = 1;
    const TYPE_STORIES_LESSON    = 2;
    const TYPE_AUDIO_BOOK        = 3;
    const TYPE_GAME              = 4;
    const TYPE_CATE              = 5;
    const TYPE_WORKSHEET         = 6;
    const TYPE_LESSON            = 7;
    const TYPE_LESSON_US         = 8;
    const TYPE_LESSON_US_PRIVATE = 11;
    const TYPE_LESSON_US_PROFILE = 12;
    const TYPE_AWARD             = 9;
    const TYPE_WORD              = 10;
    const AUDIOBOOK_APP          = 40;
    const TYPE_FLOW              = 100;
    const TYPE_STORY             = 101;
    const TYPE_COMMON_MK_TALKING = 102;

    const KEY_REDIS_VERSION = 'KEY_REDIS_VERSION_';

    public function __construct(
        LessonConnectService $lessonConnectService,
        RedisService $redisService
    ) {
        $this->lessonConnectService = $lessonConnectService;
        $this->redisService         = $redisService;
    }


    public function getDataVersionAppInfo($appId, $data)
    {
        if (!isset($data['version_story']) || !$data['version_story']) {
            $data['version_story'] = $this->getVersion($appId, self::TYPE_STORY);
        }
        if (!isset($data['version_audio']) || !$data['version_audio']) {
            $data['version_audio'] = $this->getVersion($appId, self::TYPE_AUDIO_BOOK);
        }
        if (!isset($data['version_worksheet']) || !$data['version_worksheet']) {
            $data['version_worksheet'] = $this->getVersion($appId, self::TYPE_WORKSHEET);
        }
        if (!isset($data['version_game']) || !$data['version_game']) {
            $data['version_game'] = $this->getVersion($appId, self::TYPE_GAME);
        }
        if (!isset($data['version_lesson']) || !$data['version_lesson']) {
            $data['version_lesson'] = $this->getVersion($appId, self::TYPE_STORIES_LESSON);
        }
        if (!isset($data['version_categories']) || !$data['version_categories']) {
            $data['version_categories'] = $this->getVersion($appId, self::TYPE_CATE);
        }
        if (!isset($data['version_lesson_talking']) || !$data['version_lesson_talking']) {
            $data['version_lesson_talking'] = $this->getVersion($appId, self::TYPE_STORIES_LESSON);
        }
        if (!isset($data['version_categories_talking']) || !$data['version_categories_talking']) {
            $data['version_categories_talking'] = $this->getVersion($appId, self::TYPE_CATE);
        }
        if (!isset($data['version_common_mk_talking']) || !$data['version_common_mk_talking']) {
            $data['version_common_mk_talking'] = $this->getVersion($appId, self::TYPE_COMMON_MK_TALKING);
        }
        return $data;
    }

    public function setDataRedisVersion($appId)
    {
//        $params['app_id'] = $appId;
//        $listVersion      = $this->versionApiLoadRepo->getListVersionByParams($params)->keyBy(VersionApiLoad::_TYPE)->toArray();
//        foreach ($listVersion as $type => $version) {
//            $this->redisService->hSet(self::KEY_REDIS_VERSION . $appId, $type, json_encode($version, true));
//        }
    }

    public function getDataVersion($appId, $type)
    {
        $dataVersion = $this->redisService->hGet(self::KEY_REDIS_VERSION . $appId, $type, true, true);
        if (!$dataVersion) {
            $dataVersion = $this->lessonConnectService->getVersion($appId, $type);
            if ($dataVersion) {
                $this->redisService->hSet(self::KEY_REDIS_VERSION . $appId, $type, json_encode($dataVersion, true));
            }
        }
        return $dataVersion;
    }

    public function getVersion($appId, $type)
    {
        $dataVersion = $this->getDataVersion($appId, $type);
        if (!$dataVersion || !isset($dataVersion[VersionApiLoad::_VERSION_NUMBER])) {
            return 0;
        }
        return $dataVersion[VersionApiLoad::_VERSION_NUMBER];
    }
}
