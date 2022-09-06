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
        $data         = ['app_id' => $appId, 'type_search' => implode(',', $typeSearch)];
        $url          = Config::get('environment.API_SERVICE_LESSON') . "/api/get-popular-search";
        $responseData = $this->curlService->curlGetData($url, $data, env('TOKEN_TO_SERVER'));
        $response     = json_decode($responseData, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API get-popular-search' . $responseData . " " . json_encode($data)));
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
        $responseData        = $this->curlService->curlGetData($url, $data);
        $response            = json_decode($responseData, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API v1/lesson/list-lesson-monkey-talking' . $responseData . " " . json_encode($data)));
        return [];
    }

    public function getDataCommonMkTalking($appId)
    {
        $url          = Config::get('environment.API_SERVICE_LESSON') . '/api/v1/get-common-monkey-talking';
        $data         = ['app_id' => $appId, 'json' => 1, 'is_web' => 1];
        $responseData = $this->curlService->curlGetData($url, $data);
        $response     = json_decode($responseData, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API v1/get-common-monkey-talking' . $responseData . " " . json_encode($data)));
        return [];
    }

    public function getListCategory(
        $appId,
        $deviceType,
        $subversion,
        $parentId = self::READING_COMPREHENSIONS,
        $langId = 1,
        $version = 0
    ) {
        $url                 = Config::get('environment.API_SERVICE_LESSON') . "/api/v1/categories/list";
        $data['app_id']      = $appId;
        $data['device_type'] = $deviceType;
        $data['lang_id']     = $langId;
        $data['subversion']  = $subversion;
        $data['version']     = $version;
        $data['json']        = 1;
        $data['is_web']      = 1;
        $data['parent_id']   = $parentId;
        $responseData        = $this->curlService->curlGetData($url, $data);
        $response            = json_decode($responseData, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API v1/categories/list' . $responseData . " " . json_encode($data)));
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
        $response            = $this->curlService->_curlPost($url, $data, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API get-list-activities-by-story' . json_encode($response) . " " . json_encode($data)));
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
        $data['new_story']   = 1;

        $response            = $this->curlService->_curlPost($url, $data, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API get-list-activities-by-lesson' . json_encode($response) . " " . json_encode($data)));
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
        $responseData        = $this->curlService->curlGetData($url, $data);
        $response            = json_decode($responseData, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API v1/lesson/list' . $responseData . " " . json_encode($data)));
        return [];
    }

    public function getListLessonByIds($ids)
    {
        $url          = Config::get('environment.API_SERVICE_LESSON') . "/api/get-lessons-by-ids";
        $data['ids']  = $ids;
        $responseData = $this->curlService->curlGetData($url, $data);
        $response     = json_decode($responseData, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API api/get-lessons-by-ids' . $responseData . " " . json_encode($data)));
        return [];
    }

    public function getVersion($appId, $type)
    {
        $data         = ['app_id' => $appId, 'type' => $type];
        $url          = Config::get('environment.API_SERVICE_LESSON') . "/api/get-info-version-api-load";
        $responseData = $this->curlService->curlGetData($url, $data, env('TOKEN_TO_SERVER'));
        $response     = json_decode($responseData, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API get-info-version-api-load' . $responseData . " " . json_encode($data)));
        return [];
    }

    public function getListGame()
    {
        $url          = Config::get('environment.API_SERVICE_LESSON') . "/api/get-list-game";
        $responseData = $this->curlService->curlGetData($url, [], env('TOKEN_TO_SERVER'));
        $response     = json_decode($responseData, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API get-list-game' . $responseData));
        return [];
    }
}
