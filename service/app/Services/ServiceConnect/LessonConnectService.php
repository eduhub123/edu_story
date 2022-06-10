<?php


namespace App\Services\ServiceConnect;

use App\Jobs\SendTelegram;
use App\Services\CurlService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;

class LessonConnectService
{

    const MONKEY_TALKING         = 9366;
    const READING_COMPREHENSIONS = 9365;

    private $curlService;

    public function __construct(
        CurlService $curlService
    ) {
        $this->curlService = $curlService;
    }

    public function getPopularSearch($appId, $typeSearch)
    {
        $data    = ['app_id' => $appId, 'type_search' => implode(',', $typeSearch)];
        $url     = Config::get('environment.API_SERVICE_LESSON') . "/api/get-popular-search";
        $version = $this->curlService->curlGetData($url, $data, env('TOKEN_TO_SERVER'));
        $version = json_decode($version, true);

        if (isset($version['status']) && $version['status'] == 'success') {
            return $version['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API get-popular-search'));
        return [];
    }

    public function getListLessonMonkeyTalking($appId, $deviceType, $subversion, $langId = 1, $version = 0)
    {
        $url                 = Config::get('environment.API_SERVICE_LESSON') . "/api/v1/lesson/list-lesson-monkey-talking";
        $data['app_id']      = $appId;
        $data['device_type'] = $deviceType;
        $data['lang_id']     = $langId;
        $data['subversion']  = $subversion;
        $data['version']     = $version;
        $data['json']        = 1;
        $data['is_web']      = 1;
        $lessonList          = $this->curlService->curlGetData($url, $data);
        $lessonList          = json_decode($lessonList, true);

        if (isset($lessonList['status']) && $lessonList['status'] == 'success') {
            return $lessonList['data'];
        }
        Queue::push(new SendTelegram( 'Data not showing - API v1/lesson/list-lesson-monkey-talking'));
        return [];
    }

    public function getDataCommonMkTalking($appId)
    {
        $url                 = Config::get('environment.API_SERVICE_LESSON') . '/api/v1/get-common-monkey-talking';
        $dataCommonMKTalking = $this->curlService->curlGetData($url, ['app_id' => $appId, 'json' => 1, 'is_web' => 1]);
        $dataCommonMKTalking = json_decode($dataCommonMKTalking, true);

        if (isset($dataCommonMKTalking['status']) && $dataCommonMKTalking['status'] == 'success') {
            return $dataCommonMKTalking['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API v1/get-common-monkey-talking'));
        return [];
    }

    public function getListCategory($appId, $deviceType, $subversion, $parentId = self::READING_COMPREHENSIONS, $langId = 1, $version = 0)
    {
        $url                 = Config::get('environment.API_SERVICE_LESSON') . "/api/v1/categories/list";
        $data['app_id']      = $appId;
        $data['device_type'] = $deviceType;
        $data['lang_id']     = $langId;
        $data['subversion']  = $subversion;
        $data['version']     = $version;
        $data['json']        = 1;
        $data['is_web']      = 1;
        $data['parent_id']   = $parentId;
        $categoryList        = $this->curlService->curlGetData($url, $data);
        $categoryList        = json_decode($categoryList, true);

        if (isset($categoryList['status']) && $categoryList['status'] == 'success') {
            return $categoryList['data'];
        }
        Queue::push(new SendTelegram( 'Data not showing - API v1/categories/list'));
        return [];
    }

    public function getListActStory($appId, $deviceType, $subversion, $storiesId, $inHouse, $os, $langId = 1)
    {
        $url                 = Config::get('environment.API_SERVICE_LESSON') . "/api/get-list-activities-by-story";
        $data['app_id']      = $appId;
        $data['device_type'] = $deviceType;
        $data['lang_id']     = $langId;
        $data['subversion']  = $subversion;
        $data['stories_id']  = $storiesId;
        $data['in_house']    = $inHouse;
        $data['os']          = $os;
        $listActStory        = $this->curlService->_curlPost($url, $data, true);

        if (isset($listActStory['status']) && $listActStory['status'] == 'success') {
            return $listActStory['data'];
        }
        Queue::push(new SendTelegram( 'Data not showing - API get-list-activities-by-story'));
        return [];
    }

    public function getListActLesson($appId, $deviceType, $subversion, $lessonId, $langId = 1)
    {
        $url                 = Config::get('environment.API_SERVICE_LESSON') . "/api/get-list-activities-by-lesson";
        $data['app_id']      = $appId;
        $data['device_type'] = $deviceType;
        $data['lang_id']     = $langId;
        $data['subversion']  = $subversion;
        $data['lesson_id']   = $lessonId;
        $listActLesson       = $this->curlService->_curlPost($url, $data, true);

        if (isset($listActLesson['status']) && $listActLesson['status'] == 'success') {
            return $listActLesson['data'];
        }
        Queue::push(new SendTelegram( 'Data not showing - API get-list-activities-by-lesson'));
        return [];
    }


    public function getListLesson($appId, $deviceType, $subversion, $langId = 1, $version = 0)
    {
        $url                 = Config::get('environment.API_SERVICE_LESSON') . "/api/v1/lesson/list";
        $data['app_id']      = $appId;
        $data['device_type'] = $deviceType;
        $data['lang_id']     = $langId;
        $data['subversion']  = $subversion;
        $data['version']     = $version;
        $data['json']        = 1;
        $data['is_web']      = 1;
        $lessonList          = $this->curlService->curlGetData($url, $data);
        $lessonList          = json_decode($lessonList, true);

        if (isset($lessonList['status']) && $lessonList['status'] == 'success') {
            return $lessonList['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API v1/lesson/list'));
        return [];
    }

    public function getListLessonByIds($ids)
    {
        $url         = Config::get('environment.API_SERVICE_LESSON') . "/api/get-lessons-by-ids";
        $data['ids'] = $ids;
        $lessonList  = $this->curlService->curlGetData($url, $data);
        $lessonList  = json_decode($lessonList, true);

        if (isset($lessonList['status']) && $lessonList['status'] == 'success') {
            return $lessonList['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API api/get-lessons-by-ids'));
        return [];
    }

    public function getVersion($appId, $type)
    {
        $data    = ['app_id' => $appId, 'type' => $type];
        $url     = Config::get('environment.API_SERVICE_LESSON') . "/api/get-info-version-api-load";
        $version = $this->curlService->curlGetData($url, $data, env('TOKEN_TO_SERVER'));
        $version = json_decode($version, true);

        if (isset($version['status']) && $version['status'] == 'success') {
            return $version['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API get-info-version-api-load'));
        return [];
    }

    public function getListGame()
    {
        $url      = Config::get('environment.API_SERVICE_LESSON') . "/api/get-list-game";
        $listGame = $this->curlService->curlGetData($url, [], env('TOKEN_TO_SERVER'));
        $listGame = json_decode($listGame, true);

        if (isset($listGame['status']) && $listGame['status'] == 'success') {
            return $listGame['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API get-list-game'));
        return [];
    }
}
