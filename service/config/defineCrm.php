<?php
if (env('APP_ENV') == 'live') {
    $UrlOldBehocchu = 'http://behocchu.com/';
} else {
    $UrlOldBehocchu = 'http://dev.behocchu.com/';
}
return [
    'URL_OLDBEHOCCHU' => $UrlOldBehocchu,
];
