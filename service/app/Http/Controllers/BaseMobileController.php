<?php

namespace App\Http\Controllers;

use App;
use App\Services\DetectDeviceService;
use App\Services\DetectIpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class BaseMobileController extends Controller
{

    protected $device_id;
    protected $app_id;
    protected $os;
    protected $device_type;
    protected $sv;
    protected $lang_id;
    protected $lang;
    protected $ver;
    protected $appVer;
    protected $is_vn               = false;
    protected $subversion;
    protected $isTest;
    protected $ip;
    protected $device_info;
    protected $limit               = 20;
    protected $isWeb;
    protected $isNetworkEarlyStart = false;
    protected $testDetectIp;
    protected $countryCode         = '';
    protected $profileId;

    public function __construct(
        Request $request
    ) {
        $this->language();
        $this->checkParamsDefault($request);

        if ($this->device_type == 2) {
            $this->device_type = 'hd';
        } else {
            $this->device_type = 'hdr';
        }
        $this->checkLocation();
        $this->isNetworkEarlyStart = $this->setNetworkEarlyStart();
    }

    protected function infoUser()
    {
        return $this->request()->attributes->get('userInfo');
    }

    protected function infoUserId()
    {
        $user = $this->infoUser();
        return $user['id'];
    }

    protected function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        return data_get(app('request')->all(), $key, $default);
    }

    private function convertOs($os)
    {
        $os = strtolower($os);
        switch ($os) {
            case 'android':
                return Config::get('device.android');
                break;

            case 'ios':
                return Config::get('device.ios');
                break;

            case 'win32':
                return Config::get('device.win32');
                break;

            case 'mac':
                return Config::get('device.mac');
                break;

            default:
                return 0;
                break;
        }
    }

    protected function language()
    {
        if (empty($_REQUEST['lang'])) {
            $lang = 'us';
        } else {
            $lang = !empty($this->listLang()[$_REQUEST['lang']]) ? $this->listLang()[$_REQUEST['lang']] : $this->listLang()['en-US'];
        }
        $this->lang = $lang;
        app('translator')->setLocale($lang);
        return $lang;
    }

    private function listLang()
    {
        return $arr = [
            'vi-VN' => 'vn',
            'en-US' => 'us',
            'th-TH' => 'th',
            'id-ID' => 'id'
        ];
    }

    protected function checkParamsDefault($request)
    {
        header('Content-type: application/json');
        $this->app_id       = (int)$request->input('app_id');
        $this->device_type  = $request->input('device_type');
        $this->device_id    = $request->input('device_id');
        $this->ver          = $request->input('ver');
        $this->subversion   = $request->input('subversion');
        $this->device_info  = $request->input('device_info');
        $this->lang_id     = $request->input('lang_id');
        $this->sv           = intval($request->input('sv'));
        $this->isTest       = $request->input('is_test');
        $this->os           = $this->convertOs($request->input('os'));
        $this->appVer       = $request->input('app_ver');
        $this->isWeb        = $request->input('is_web');
        $this->profileId    = $request->input('profile_id');
        $this->testDetectIp = $request->input('test_detect_ip');

        if (!$this->isWeb) {
            if (!$this->app_id || !$this->device_type || !is_numeric($this->app_id)) {
                $this->message = __('app.invalid_params');
                die($this->responseNewData($_REQUEST));
            }
        }
    }

    private function setNetworkEarlyStart()
    {
        $detectDeviceService = new DetectDeviceService();
        $ipList = ['113.190.232.224', '118.70.176.20', '118.70.186.162', '222.252.17.100', '42.113.143.186', '58.186.61.150', '222.252.28.108'];
        if (in_array($this->ip, $ipList) || $detectDeviceService->checkDeviceInhouse($this->device_id)) {
            return true;
        }
        return false;
    }

    public function checkLocation()
    {
        $detectIpService   = new DetectIpService();
        $this->ip          = $ip = $detectIpService->ip_address();
        $this->is_vn       = $detectIpService->isVn($ip);
        $locationFromIP    = $detectIpService->getLocationFormIp();
        $this->countryCode = $locationFromIP;
    }

}
