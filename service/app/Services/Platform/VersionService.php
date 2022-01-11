<?php

namespace App\Services\Platform;

use App\Jobs\UpdateRedisVersion;
use App\Models\Mongo\ProfileHasLesson;
use App\Models\Platform\VersionApiLoad;
use App\Repositories\Mongo\ProfileHasLessonRepository;
use App\Repositories\Platform\VersionApiLoadRepository;
use App\Services\RedisService;
use Illuminate\Support\Facades\Queue;

class VersionService
{
    private $profileHasLessonRepo;
    private $versionApiLoadRepo;
    private $redisService;

    const KEY_REDIS_VERSION                    = 'KEY_REDIS_VERSION_';
    const KEY_REDIS_ALL_VERSION_LESSON_PROFILE = 'KEY_REDIS_ALL_VERSION_LESSON_PROFILE_';

    const LIST_TYPE_CATE = [
        [
            'language_id' => 1,
            'type'        => 30,
        ],
        [
            'language_id' => 4,
            'type'        => 31,
        ],
        [
            'language_id' => 21,
            'type'        => 32,
        ],
        [
            'language_id' => 12,
            'type'        => 34,
        ],
        [
            'language_id' => 8,
            'type'        => 35,
        ],
        [
            'language_id' => 7,
            'type'        => 36,
        ],
        [
            'language_id' => 9,
            'type'        => 37,
        ],
        [
            'language_id' => 10,
            'type'        => 38,
        ]
    ];

    public function __construct(
        VersionApiLoadRepository $versionApiLoadRepo,
        RedisService $redisService
    ) {
        $this->versionApiLoadRepo   = $versionApiLoadRepo;
        $this->redisService         = $redisService;
    }

    public function getDataAllVersionLessonProfile($appId, $profileId)
    {
        $lastVersion_US = $this->getVersion($appId, VersionApiLoad::TYPE_LESSON_US);
        $lastVersion    = $this->getVersion($appId, VersionApiLoad::TYPE_LESSON);

        $profileHasLessonByProfileId = $this->profileHasLessonRepo->getByProfileId($profileId);
        $versionProfile              = 0;
        if ($profileHasLessonByProfileId) {
            $versionProfile = $profileHasLessonByProfileId[ProfileHasLesson::_VERSION_PROFILE] ?? 0;
        }
        if (!$versionProfile || $versionProfile < $lastVersion) {
            $versionLessonUs = $lastVersion_US;
        } else {
            $versionLessonUs = $versionProfile;
        }

        return [
            'version_lesson_us' => $versionLessonUs,
            'version_lesson'    => $lastVersion,
        ];
    }

    public function getAllVersionLessonProfile($appId, $profileId)
    {
        $lesson = $this->redisService->hGet(self::KEY_REDIS_ALL_VERSION_LESSON_PROFILE . $appId, $profileId);
        $lesson = json_decode($lesson, true);
        if (!$lesson) {
            $lesson = $this->getDataAllVersionLessonProfile($appId, $profileId);
            if ($lesson) {
                $this->redisService->hSet(self::KEY_REDIS_ALL_VERSION_LESSON_PROFILE . $appId, $profileId, json_encode($lesson, true));
            }
        }
        return $lesson;
    }

    public function getDataListVersion($appId)
    {
        $params['app_id'] = $appId;
        return $this->versionApiLoadRepo->getListVersionByParams($params)->keyBy(VersionApiLoad::_TYPE)->toArray();
    }

    public function getDataRedisVersion($appId)
    {
        $listRedisVersion = $this->redisService->hGetAll(self::KEY_REDIS_VERSION . $appId);
        $listVersion      = [];
        foreach ($listRedisVersion as $type => $version) {
            $listVersion[$type] = json_decode($version, true);
        }
        return $listVersion;
    }

    public function setDataRedisVersion($appId)
    {
        $params['app_id'] = $appId;
        $listVersion      = $this->versionApiLoadRepo->getListVersionByParams($params)->keyBy(VersionApiLoad::_TYPE)->toArray();
        foreach ($listVersion as $type => $version) {
            $this->redisService->hSet(self::KEY_REDIS_VERSION . $appId, $type, json_encode($version, true));
        }
    }

    public function getDataVersion($appId, $type)
    {
        $dataVersion = $this->redisService->hGet(self::KEY_REDIS_VERSION . $appId, $type);
        $dataVersion = json_decode($dataVersion, true);
        if (!$dataVersion) {
            Queue::push(new UpdateRedisVersion($appId));
            $dataVersion = $this->versionApiLoadRepo->getVersion($appId, $type);
        }
        return $dataVersion;
    }

    public function getVersion($appId, $type)
    {
        if ($type == VersionApiLoad::TYPE_LESSON_US) {
            return $this->getVersionUS($appId);
        }
        $lastVersion = $this->getDataVersion($appId, $type);
        if (!$lastVersion || !isset($lastVersion[VersionApiLoad::_VERSION_NUMBER])) {
            return 0;
        }
        return $lastVersion[VersionApiLoad::_VERSION_NUMBER];
    }

    public function getVersionUS($appId)
    {
        $lastVersionUS    = $this->getDataVersion($appId, VersionApiLoad::TYPE_LESSON_US);
        $versionUS        = $lastVersionUS[VersionApiLoad::_VERSION_NUMBER];
        $lastVersionNotUS = $this->getDataVersion($appId, VersionApiLoad::TYPE_LESSON);
        $versionNotUS     = $lastVersionNotUS[VersionApiLoad::_VERSION_NUMBER];
        if (!$versionNotUS) {
            return false;
        }
        if ($versionUS < $versionNotUS) {
            return $versionNotUS;
        }
        return $versionUS;
    }

    public function getVersionUSProfile($appId)
    {
        $lastVersionUS      = $this->versionApiLoadRepo->getVersion($appId, VersionApiLoad::TYPE_LESSON_US);
        $versionUS          = $lastVersionUS[VersionApiLoad::_VERSION_NUMBER] ?? 0;
        $lastVersionProfile = $this->versionApiLoadRepo->getVersion($appId, VersionApiLoad::TYPE_LESSON_US_PROFILE);
        $versionProfile     = $lastVersionProfile[VersionApiLoad::_VERSION_NUMBER] ?? 0;

        if (!$versionProfile || $versionProfile < $versionUS) {
            $versionProfile = $versionUS;
        }
        return [$versionProfile, $versionUS];
    }

    public function saveVersion($appId, $type, $version = null, $path = null)
    {
        if ($type == VersionApiLoad::TYPE_LESSON_US) {
            if (!$version) {
                return false;
            }
            $versionInfoLesson = $this->versionApiLoadRepo->saveVersion($appId, VersionApiLoad::TYPE_LESSON, $version, $path);
            if ($versionInfoLesson) {
                $this->redisService->hSet(self::KEY_REDIS_VERSION . $appId, VersionApiLoad::TYPE_LESSON, json_encode($versionInfoLesson, true));
            }
        }
        $versionInfo = $this->versionApiLoadRepo->saveVersion($appId, $type, $version, $path);
        if ($versionInfo) {
            $this->redisService->hSet(self::KEY_REDIS_VERSION . $appId, $type, json_encode($versionInfo, true));
        }
        return $versionInfo;
    }
}
