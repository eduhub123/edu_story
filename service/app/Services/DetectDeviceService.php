<?php

namespace App\Services;

class DetectDeviceService
{
    const KEY_REDIS_DEVICE_IN_HOUSE = 'KEY_REDIS_DEVICE_IN_HOUSE';

    public function checkDeviceInhouse($deviceId)
    {
        $redisService      = new RedisService();
        $listDeviceInHouse = $redisService->get(self::KEY_REDIS_DEVICE_IN_HOUSE, true);
        $listDeviceInHouse = is_array($listDeviceInHouse) ? $listDeviceInHouse : [];

        if (in_array($deviceId, $listDeviceInHouse)) {
            return true;
        }
        return false;
    }

}
