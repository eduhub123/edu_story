<?php

namespace App\Services\ServiceConnect;

use App\Jobs\SendTelegram;
use App\Services\CurlService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;

class CrmConnectService
{
    private $curlService;

    public function __construct(
        CurlService $curlService
    ) {
        $this->curlService = $curlService;
    }

    public function getListPay($appId, $countryCode)
    {
        $data['app_id']       = $appId;
        $data['country_code'] = $countryCode;
        $url                  = Config::get('environment.API_SERVICE_CRM') . '/api/list-pay';
        $response             = $this->curlService->curlGetData($url, $data, env('TOKEN_TO_SERVER'));
        $response             = json_decode($response, true);
        if (isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }
        Queue::push(new SendTelegram( 'Data not showing - API list-pay'));
        return [];
    }
}
