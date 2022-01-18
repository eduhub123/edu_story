<?php


namespace App\Services\ServiceConnect;

use App\Services\CurlService;
use Illuminate\Support\Facades\Config;

class LessonConnectService
{
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
        return [];
    }
}
