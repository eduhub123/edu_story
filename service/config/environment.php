<?php
if (env('APP_ENV') == 'live') {
    $urlConnectServiceAuth    = 'https://www.auth.monkeyuni.com';
    $urlConnectServiceDevelop = 'https://www.api.monkeyuni.net';
    $urlConnectServiceMail    = 'https://email.monkeyuni.net';
    $urlConnectServiceCRM     = 'https://crmv2.monkeyuni.net/';
    $urlConnectServiceSupport = 'https://www.ticket.monkeyuni.com';
    $urlConnectServiceLms     = 'https://lms.monkeyuni.net';
    $urlConnectServiceApp     = 'https://app.monkeyuni.net/user';
    $urlConnectServiceMedia   = 'https://media.dev.monkeyuni.net';
    $domainApiBeHocChu        = 'https://www.api.monkeyuni.net/';
    $downloadResourceS3       = 'https://s3-ap-southeast-1.amazonaws.com/monkey-media.monkeyuni.com';
    $urlDisplayCdn            = 'https://vysqy4zclvobj.vcdn.cloud/';
} elseif (env('APP_ENV') == 'dev') {
    $urlConnectServiceAuth    = 'https://auth.dev.monkeyuni.com';
    $urlConnectServiceDevelop = 'https://api.dev.monkeyuni.net';
    $urlConnectServiceMail    = 'https://email.dev.monkeyuni.com';
    $urlConnectServiceCRM     = 'https://crm.dev.monkeyuni.net/';
    $urlConnectServiceSupport = 'https://ticket.dev.monkeyuni.com';
    $urlConnectServiceLms     = 'https://lms.monkeyuni.net/';
    $urlConnectServiceApp     = 'https://api.dev.monkeyuni.com/user';
    $urlConnectServiceMedia   = 'https://media.dev.monkeyuni.net';
    $domainApiBeHocChu        = 'https://www.api.dev.monkeyuni.net/';
    $downloadResourceS3       = 'https://s3-ap-southeast-1.amazonaws.com/monkey-media-test.monkeyuni.com';
    $urlDisplayCdn            = 'https://vysqy4zclvobj.vcdn.cloud/';
} else {
    $urlConnectServiceAuth    = 'http://localhost:8088';
    $urlConnectServiceDevelop = 'http://localhost:8000';
    $urlConnectServiceMail    = 'http://localhost:8080';
    $urlConnectServiceCRM     = 'http://localhost:8090/api/';
    $urlConnectServiceSupport = 'http://localhost:9000';
    $urlConnectServiceLms     = 'http://localhost:8093';
    $urlConnectServiceApp     = 'http://localhost:9098';
    $urlConnectServiceMedia   = 'http://localhost:8089';
    $domainApiBeHocChu        = 'http://localhost:8000';
    $downloadResourceS3       = 'https://s3-ap-southeast-1.amazonaws.com/monkey-media-test.monkeyuni.com';
    $urlDisplayCdn            = 'https://vysqy4zclvobj.vcdn.cloud/';
}

$urlCleverTap = 'https://sg1.api.clevertap.com';

return [
    'API_SERVICE_AUTH'                               => $urlConnectServiceAuth,
    'API_CLEVERTAP'                                  => $urlCleverTap,
    'API_SERVICE_DEVELOP'                            => $urlConnectServiceDevelop,
    'API_SERVICE_MAIL'                               => $urlConnectServiceMail,
    'API_SERVICE_CRM'                                => $urlConnectServiceCRM,
    'API_SERVICE_SUPPORT'                            => $urlConnectServiceSupport,
    'API_SERVICE_LMS'                                => $urlConnectServiceLms,
    'API_SERVICE_APP'                                => $urlConnectServiceApp,
    'DOMAIN_API_BEHOCCHU'                            => $domainApiBeHocChu,
    'DOMAIN_DOWNLOAD_RESOURCE'                       => $downloadResourceS3,
    'API_SERVICE_TELEGRAM'                           => 'https://api.telegram.org/bot',
    'TELEGRAM_BOT_TOKEN_CREATE_ORDER'                => '994444984:AAHz8WSNWw3llxIssaqNVJ-sPF_HT9812YA',
    'TELEGRAM_CHAT_ID_CREATE_ORDER'                  => '-450915807',
    'TELEGRAM_BOT_TOKEN_GENERATE_OTP_DELETE_ACCOUNT' => '721256609:AAE8Ve3vckZW50nBgNVt7nCY_hXuhDXQFaY',
    'TELEGRAM_CHAT_ID_GENERATE_OTP_DELETE_ACCOUNT'   => '-417922862',
    "TELEGRAM_CHANNEL_ID_MONITOR_DATA"               => "-1001309112306",
    "TELEGRAM_BOT_TOKEN_MONITOR_DATA"                => "1093228941:AAEP0bYFr59eIjmcMFK3MSD9XChIsA5moSI",
    'API_SERVICE_MEDIA'                              => $urlConnectServiceMedia,
    'URL_DISPLAY_CDN'                                => $urlDisplayCdn
];
