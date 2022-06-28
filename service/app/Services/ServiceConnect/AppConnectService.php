<?php

namespace App\Services\ServiceConnect;

use App\Jobs\SendTelegram;
use App\Services\CurlService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;

class AppConnectService
{
    private $curlService;

    public function __construct(
        CurlService $curlService
    ) {
        $this->curlService = $curlService;
    }

    public function getListApp()
    {
        $url          = Config::get('environment.API_SERVICE_APP') . "/api/get-list-app";
        $responseData = $this->curlService->curlGetData($url, [], env('TOKEN_TO_SERVER'));
        $response     = json_decode($responseData, true);

        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API get-list-app' . $responseData));
        return [];
    }


    public function getInfoProfile($profileId)
    {
        $data['profile_id']       = $profileId;

        $url     = Config::get('environment.API_SERVICE_APP') . "/api/get-info-profile";
        $version = $this->curlService->curlGetData($url, $data, env('TOKEN_TO_SERVER'));
        $version = json_decode($version, true);

        if (isset($version['status']) && $version['status'] == 'success') {
            return $version['data'];
        }

        Queue::push(new SendTelegram('Data not found profileId: ' . $profileId . '- API get-info-profile'));
        return [];
    }
}
