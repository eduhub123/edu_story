<?php


namespace App\Services\ServiceConnect;


use App\Services\CurlService;

class AppConnectService
{
    private $curlService;

    public function __construct(
        CurlService $curlService
    ) {
        $this->curlService = $curlService;
    }

}
