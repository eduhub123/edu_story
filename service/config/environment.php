<?php
if (env('APP_ENV') == 'live') {
    $urlConnectServiceMedia  = 'https://media.dev.monkeyuni.net';
    $urlConnectServiceLesson = 'http://10.240.0.42:9097';
    $urlConnectServiceApp    = 'https://app.monkeyuni.net/user';
    $downloadResourceS3      = 'https://s3-ap-southeast-1.amazonaws.com/monkey-media.monkeyuni.com';
    $urlDisplayCdn           = 'https://vysqy4zclvobj.vcdn.cloud/';
} elseif (env('APP_ENV') == 'dev') {
    $urlConnectServiceMedia  = 'https://media.dev.monkeyuni.net';
    $urlConnectServiceLesson = 'https://api.dev.monkeyuni.com/lesson';
    $urlConnectServiceApp    = 'https://api.dev.monkeyuni.com/user';
    $downloadResourceS3      = 'https://s3-ap-southeast-1.amazonaws.com/monkey-media-test.monkeyuni.com';
    $urlDisplayCdn           = 'https://vysqy4zclvobj.vcdn.cloud/';
} elseif (env('APP_ENV') == 'product') {
    $urlConnectServiceMedia  = 'https://media.dev.monkeyuni.net';
    $urlConnectServiceLesson = 'https://api.dev.monkeyuni.com/product_lesson';
    $urlConnectServiceApp    = 'https://api.dev.monkeyuni.com/product_user';
    $downloadResourceS3      = 'https://s3-ap-southeast-1.amazonaws.com/monkey-media-test.monkeyuni.com';
    $urlDisplayCdn           = 'https://vysqy4zclvobj.vcdn.cloud/';
}else {
    $urlConnectServiceMedia  = 'http://localhost:8089';
    $urlConnectServiceLesson = 'http://localhost:8099';
    $urlConnectServiceApp    = 'http://localhost:9098';
    $downloadResourceS3      = 'https://s3-ap-southeast-1.amazonaws.com/monkey-media-test.monkeyuni.com';
    $urlDisplayCdn           = 'https://vysqy4zclvobj.vcdn.cloud/';
}

$urlCleverTap = 'https://sg1.api.clevertap.com';

return [
    'API_SERVICE_MEDIA'                              => $urlConnectServiceMedia,
    'API_SERVICE_LESSON'                             => $urlConnectServiceLesson,
    'API_SERVICE_APP'                                => $urlConnectServiceApp,
    'DOMAIN_DOWNLOAD_RESOURCE'                       => $downloadResourceS3,
    'URL_DISPLAY_CDN'                                => $urlDisplayCdn,
    'API_SERVICE_TELEGRAM'                           => 'https://api.telegram.org/bot',
    'TELEGRAM_BOT_TOKEN_CREATE_ORDER'                => '994444984:AAHz8WSNWw3llxIssaqNVJ-sPF_HT9812YA',
    'TELEGRAM_CHAT_ID_CREATE_ORDER'                  => '-450915807',
    'TELEGRAM_BOT_TOKEN_GENERATE_OTP_DELETE_ACCOUNT' => '721256609:AAE8Ve3vckZW50nBgNVt7nCY_hXuhDXQFaY',
    'TELEGRAM_CHAT_ID_GENERATE_OTP_DELETE_ACCOUNT'   => '-417922862',
    "TELEGRAM_CHANNEL_ID_MONITOR_DATA"               => "-1001309112306",
    "TELEGRAM_BOT_TOKEN_MONITOR_DATA"                => "1093228941:AAEP0bYFr59eIjmcMFK3MSD9XChIsA5moSI"
];
