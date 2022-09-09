<?php
/**
 * Created by PhpStorm.
 * User: tuananh
 * Date: 17/01/2019
 * Time: 14:24
 */

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Ixudra\Curl\Facades\Curl;
use App\Models\Globals\ListApp;

class CurlService
{

    private $request;

    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    public function _curlPostAuth($url, $data, $checkHeader = false, $token = '')
    {
        if ($checkHeader) {
            $response = Curl::to($url)
                ->withData(json_encode($data))
                ->withHeader('Content-Type:application/json')
                ->withHeader('Authorization:' . $token)
                ->post();
            return $response;
        }
        $response = Curl::to($url)
            ->withData($data)
            ->post();
        return $response;
    }

    public function _curlPost($url, $data, $checkHeader = false, $token = '')
    {
        if (!$token) {
            $token = $this->request->header('token');
        }
        if (!$token) {
            $token = env('TOKEN_TO_SERVER');
        }
        if ($checkHeader) {
            $response = Curl::to($url)
                ->withData($data)
                ->withHeader('Content-Type:application/json')
                ->withHeader('Token:' . $token)
                ->asJson(true)
                ->post();
            return $response;
        }
        $response = Curl::to($url)
            ->withData($data)
            ->post();
        return $response;
    }

    public function _curlGet($url)
    {
        $response = Curl::to($url)
            ->get();
        return $response;
    }

    public function curlGetData($url, $data = [], $token = null)
    {
        if (!$token) {
            $token = $this->request->header('token');
        }
        if (!$token) {
            $token = env('TOKEN_TO_SERVER');
        }
        if ($token) {
            $response = Curl::to($url)
                ->withData($data)
                ->withHeader('Token:' . $token)
                ->get();

            return $response;
        }

        $response = Curl::to($url)
            ->withData($data)
            ->get();

        return $response;
    }

    public function curlPostUploadFile($url, $data = [], $token = null, $file = null)
    {
        if (!$token) {
            $token = $this->request->header('token');
        }
        if (!$token) {
            $token = env('TOKEN_TO_SERVER');
        }
        if ($token) {
            $response = Curl::to($url)
                ->withData($data)
                ->withHeader('Token:' . $token)
                ->withFile('file', $file)
                ->post();
            return $response;
        }
        $response = Curl::to($url)
            ->withData($data)
            ->withFile('file', $file)
            ->post();

        return $response;
    }

    public function curlPostUploadListFile($url, $data = [], $token = null, $listFile = [])
    {
        if (!$token) {
            $token = $this->request->header('token');
        }
        if (!$token) {
            $token = env('TOKEN_TO_SERVER');
        }
        $response = Curl::to($url);
        if ($token) {
            $response = $response->withHeader('Token:' . $token);
        }
        $response = $response->withData($data);

        foreach ($listFile as $key => $file) {
            $response = $response->withFile('file['.$key.']', $file);
        }
        return $response->post();
    }

    public function curlToCleverTapGet($appId, $url)
    {
        $url = Config::get('environment.API_CLEVERTAP') . $url;

        $configAccount =
            [
                ListApp::APP_ID_LTR => [
                    "account_id" => "6Z8-R8R-585Z",
                    "passcode" => "WVE-IQC-UIKL"
                ],

                ListApp::APP_ID_MM => [
                    "account_id" => "RZ9-6W6-785Z",
                    "passcode" => "EVS-SKY-IIKL"
                ],
                ListApp::APP_ID_MS_EN => [
                    "account_id" => "W46-6KK-685Z",
                    "passcode" => "IAC-SWB-OIKL"
                ],
                ListApp::APP_ID_MS_VN => [
                    "account_id" => "RZ9-W5K-785Z",
                    "passcode" => "EVS-QEB-IIKL"
                ],

            ];

        if (!isset($configAccount[$appId])) {
            return false;
        }

        $response = Curl::to($url)
            ->withHeader('X-CleverTap-Account-Id:' . $configAccount[$appId]['account_id'])
            ->withHeader('X-CleverTap-Passcode:' . $configAccount[$appId]['passcode'])
            ->withHeader('Content-Type:application/json; charset=utf-8')
            ->get();

        if (is_string($response)) {
            $response = json_decode($response, true);
        }
        return $response;
    }

    public function curlToCleverTap($appId, $url, $data)
    {
        $url = Config::get('environment.API_CLEVERTAP') . $url;
        if (is_array($data)) {
            $data = json_encode($data);
        }

        $configAccount =
            [
                ListApp::APP_ID_LTR => [
                    "account_id" => "6Z8-R8R-585Z",
                    "passcode" => "WVE-IQC-UIKL"
                ],

                ListApp::APP_ID_MM => [
                    "account_id" => "RZ9-6W6-785Z",
                    "passcode" => "EVS-SKY-IIKL"
                ],
                ListApp::APP_ID_MS_EN => [
                    "account_id" => "W46-6KK-685Z",
                    "passcode" => "IAC-SWB-OIKL"
                ],
                ListApp::APP_ID_MS_VN => [
                    "account_id" => "RZ9-W5K-785Z",
                    "passcode" => "EVS-QEB-IIKL"
                ],
                ListApp::APP_ID_MK => [
                    "account_id" => "W88-R8W-Z95Z",
                    "passcode" => "IYE-IQE-SXKL"
                ]
            ];

        if (!isset($configAccount[$appId])) {
            return false;
        }

        $response = Curl::to($url)
            ->withData($data)
            ->withHeader('X-CleverTap-Account-Id:' . $configAccount[$appId]['account_id'])
            ->withHeader('X-CleverTap-Passcode:' . $configAccount[$appId]['passcode'])
            ->withHeader('Content-Type:application/json; charset=utf-8')
            ->post();

        if (is_string($response)) {
            $response = json_decode($response, true);
        }
        return $response;
    }

}
