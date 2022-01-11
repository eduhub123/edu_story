<?php


namespace App\Services\ServiceConnect;


use App\Services\CurlService;
use Illuminate\Support\Carbon;

class AppConnectService
{
    private $curlService;

    public function __construct(
        CurlService $curlService
    ) {
        $this->curlService = $curlService;
    }

    public function getAgeProfile($profileId)
    {
        $infoProfile = $this->getDataInfoProfile($profileId);
        $age         = 3;
        if ($infoProfile) {
            $age = $infoProfile['age'];
            if ($infoProfile['date_of_birth']) {
                $dateOfBirth = Carbon::createFromTimestamp($infoProfile['date_of_birth']);
                $age         = date_diff($dateOfBirth, date_create('today'))->y;
            }
        }
        return $age;
    }

    public function getDataInfoProfile($profileId)
    {
        $dataResponse = $this->getInfoProfile($profileId);
        if (isset($dataResponse['status']) && $dataResponse['status'] == 'success') {
            return $dataResponse['data'];
        }
        return false;
    }

    public function getInfoProfile($profileId)
    {
        $url                = config('environment.API_SERVICE_APP') . "/api/get-info-profile";
        $data['profile_id'] = $profileId;
        $dataResponse       = $this->curlService->curlGetData($url, $data);
        return json_decode($dataResponse, true);
    }
}
