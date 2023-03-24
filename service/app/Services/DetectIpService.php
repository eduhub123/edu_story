<?php

namespace App\Services;

use Ip2location\IP2LocationLaravel\IP2LocationLaravel;

class DetectIpService
{
    protected $is_vn = false;

    public function getInfoLocationFormIp($ip)
    {
        $info = $this->detectLocationFromIpShowDetail($ip);

        $data                    = [];
        $data['ip']              = $ip;
        $data['countryCodeHere'] = $info['countryCode'];
        $data['country_name']    = $info['countryName'];
        $data['region_name']     = $info['regionName'];
        $data['city_name']       = $info['cityName'];
//        $data['latitude']        = (float)$info['latitude'];
//        $data['longitude']       = (float)$info['longitude'];
        $data['latitude']        = (float) '0';
        $data['longitude']       = (float) '0';
        $data['is_vn']           = $this->isVn($ip);

        return $data;
    }

    public function isVn($ip = "")
    {
        if (!$ip) {
            $ip = $this->ip_address();
        }

        list($ip, $country_code, $country_name) = $this->detectLocationFromIp($ip);
        if (!$country_code || $country_code != 'VN') {
            return false;
        }
        return true;
    }

    public function isMalay($ip = "")
    {
        if (!$ip) {
            $ip = $this->ip_address();
        }

        list($ip, $countryCode, $country_name) = $this->detectLocationFromIp($ip);
        if (!$countryCode || $countryCode != 'MY' || $countryCode != 'MYS') {
            return false;
        }
        return true;
    }

    public function detectLocationFromIp($ip)
    {
        $ip2location = new IP2LocationLaravel();
        $rows = $ip2location->get($ip);
        if (!$rows) {
            return false;
        }
        $rows = (array) $rows;
        return [$rows['ipAddress'], $rows['countryCode'], $rows['countryName']];
    }

    public function detectLocationFromIpShowDetail($ip)
    {
        $ip2location = new IP2LocationLaravel();
        $rows = $ip2location->get($ip);
        if (!$rows) {
            return false;
        }
        return (array) $rows;
    }

    public function getLocationFormIp($ip = "")
    {
//        $ip = '113.190.232.224';
        if (!$ip) {
            $ip   = $this->ip_address();
        }
        $ip2location = new IP2LocationLaravel();
        $rows = $ip2location->get($ip);
        if (!$rows) {
            return false;
        }
        $rows = (array) $rows;
        return $rows['countryCode'];
    }

    public function ip_address()
    {
//        return $ip_address = "206.189.42.53";
        $ip_address = "";
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address_parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($ip_address_parts as $ip) {
                if ($ip != '127.0.0.1') {
                    $ip_address = $ip;
                    break;
                }
            }
        } else {
            $ip_address = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0;
        }

        return $ip_address;
    }

    public function checkExistAddress($text)
    {
        if (strlen(trim($text)) <= 3) {
            return false;
        }

        $text = str_replace(' ', '%20', $text);

        $url = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=' . $text . '&inputtype=textquery&fields=photos,formatted_address,name,opening_hours,rating&key=' . Config::get('google.GOOGLE_MAP_API_KEY');

        $response = Curl::to($url)->get();
        $info = json_decode($response, true);

        if ($info['status'] == "OK") {
            return true;
        }

        return false;

    }

}
