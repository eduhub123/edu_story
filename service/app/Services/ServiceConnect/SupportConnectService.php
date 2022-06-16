<?php

namespace App\Services\ServiceConnect;

use App\Jobs\SendTelegram;
use App\Services\CurlService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;

class SupportConnectService
{
    private $curlService;

    public function __construct(
        CurlService $curlService
    ) {
        $this->curlService = $curlService;
    }

    public function getListAppProblem()
    {
        $url          = Config::get('environment.API_SERVICE_SUPPORT') . "/api/get-list-app-problem?status=1";
        $responseData = $this->curlService->curlGetData($url);
        $response     = json_decode($responseData, true);
        if (isset($response['status']) && $response['status'] = 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram('Data not showing - API get-list-app-problem' . $responseData));
        return [];
    }
}
