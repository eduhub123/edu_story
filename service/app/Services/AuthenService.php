<?php

namespace App\Services;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Config;

class AuthenService
{

    private $redisService;

    public function __construct(
        RedisService $redisService
    ) {
        $this->redisService = $redisService;
    }

    public function setToken($data = [], $timeExpire = '') 
    {
        if ($timeExpire) {
            $data['exp'] = $timeExpire;
        }
        $encoded     = JWT::encode($data, Config::get('constants.key_token'));
        return $encoded;
    }

    public function deToken($access_token) 
    {
        try {
            $tokenArray = JWT::decode($access_token, Config::get('constants.key_token'), array('HS256'));
            return $tokenArray;
        } catch (ExpiredException | \Exception $e) {
            return false;
        }
    }

    public function setTimeExpireForToken () 
    {
        return time() + 60 * 60 * 24 * 30;
    }

    public function isMaxOnActiveDeviceLogin($userId, $deviceId, $maxDeviceLogin, $appId)
    {
        $timeNow = $this->getTimeNow(date("H-i-s"));
        $keyDeviceOnActiveByUserId = 'deviceOnActiveByUserID_';
        $keyDeviceOnActiveByUserIdAndDevice = 'deviceOnActiveByUserIdAndDevice_';

        $deviceAllowedLogin = $this->redisService->get($keyDeviceOnActiveByUserId.$userId.'_'.$appId) ? $this->redisService->get($keyDeviceOnActiveByUserId . $userId.'_'.$appId) : array();

        if ((!in_array($deviceId, $deviceAllowedLogin) && count($deviceAllowedLogin) < $maxDeviceLogin) ||
            (in_array(9, $deviceAllowedLogin) && !in_array($deviceId, $deviceAllowedLogin) && count($deviceAllowedLogin) < $maxDeviceLogin + 1 && !in_array($deviceId, $deviceAllowedLogin))
        ) {
            if ((int) $deviceId) {
                array_push($deviceAllowedLogin, (int) $deviceId);
            }
        }
        if(in_array(9, $deviceAllowedLogin) ) {
            if (count($deviceAllowedLogin) == $maxDeviceLogin + 1 && !in_array($deviceId, $deviceAllowedLogin)) {
                return true;
            }
        } else {
            if (count($deviceAllowedLogin) == $maxDeviceLogin && !in_array($deviceId, $deviceAllowedLogin)) {
                return true;
            }
        }

        $this->redisService->set($keyDeviceOnActiveByUserId.$userId.'_'.$appId, $deviceAllowedLogin, 24 * 60 * 60 - $timeNow);
        $this->redisService->set($keyDeviceOnActiveByUserIdAndDevice.$userId."_".$deviceId, true, 24 * 60 * 60 - $timeNow);

        return false;
    }

    public function getTimeNow($timeNow)
    {
        $array = explode('-', $timeNow);
        $time  = (int)($array[0]) * 60 * 60 + (int)($array[1]) * 60 + (int)$array[2];
        return $time;
    }

    public function verifyTokenChangePwForWeb($info, $allowSet = false)
    {
        $keyTokenToChangePw = 'TOKEN_CHANGE_PW_FOR_WEB';
        $tokenToChangePw = $this->redisService->get($keyTokenToChangePw . '_' . $info);

        $randomKeyTokenToChangePw = rand(0, 100000);
        if (!$tokenToChangePw && $allowSet) {
            $tokenToChangePw = $this->redisService->set($keyTokenToChangePw . '_' . $info, $randomKeyTokenToChangePw, 60 * 60);
        }

        return $tokenToChangePw ? $tokenToChangePw : $randomKeyTokenToChangePw;
    }
}
