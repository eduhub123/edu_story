<?php
if (env('APP_ENV') == 'live') {
    $urlConnectServiceMedia   = 'https://media.monkeyuni.net';
    $urlConnectServiceCrm     = 'https://crm.dev.monkeyuni.net';
    $urlConnectServiceSupport = 'https://ticket.monkeyuni.net';

    $urlConnectServiceApp    = 'http://edu-app.app.svc.cluster.local:80';
    $urlConnectServiceLesson = 'http://edu-lesson.app.svc.cluster.local:80';

    $downloadResourceS3 = 'https://s3-ap-southeast-1.amazonaws.com/monkey-media.monkeyuni.com';
    $urlDisplayCdn      = 'https://vnmedia2.monkeyuni.net/';
} elseif (env('APP_ENV') == 'dev') {
    $urlConnectServiceMedia   = 'https://media.dev.monkeyuni.net';
    $urlConnectServiceCrm     = 'https://crm.dev.monkeyuni.net';
    $urlConnectServiceSupport = 'https://ticket.dev.monkeyuni.com';

    $urlConnectServiceApp    = 'https://api.dev.monkeyuni.com/user';
    $urlConnectServiceLesson = 'https://api.dev.monkeyuni.com/lesson';

    $downloadResourceS3 = 'https://s3-ap-southeast-1.amazonaws.com/monkey-media-test.monkeyuni.com';
    $urlDisplayCdn      = 'https://vnmediadev.monkeyuni.net/';
} else {
    $urlConnectServiceMedia   = 'http://localhost:8089';
    $urlConnectServiceCrm     = 'https://crm.dev.monkeyuni.net';
    $urlConnectServiceSupport = 'https://ticket.dev.monkeyuni.com';

    $urlConnectServiceApp    = 'http://localhost:9098';
    $urlConnectServiceLesson = 'http://localhost:8099';

    $downloadResourceS3 = 'https://s3-ap-southeast-1.amazonaws.com/monkey-media-test.monkeyuni.com';
    $urlDisplayCdn      = 'https://vnmediadev.monkeyuni.net/';
}

return [
    'API_SERVICE_MEDIA'   => $urlConnectServiceMedia,
    'API_SERVICE_CRM'     => $urlConnectServiceCrm,
    'API_SERVICE_SUPPORT' => $urlConnectServiceSupport,

    'API_SERVICE_LESSON' => $urlConnectServiceLesson,
    'API_SERVICE_APP'    => $urlConnectServiceApp,

    'DOMAIN_DOWNLOAD_RESOURCE' => $downloadResourceS3,
    'URL_DISPLAY_CDN'          => $urlDisplayCdn,

    'API_SERVICE_TELEGRAM'         => 'https://api.telegram.org/bot',
    'TELEGRAM_BOT_TOKEN_ERROR_APP' => '1487939909:AAGKsgk7CYtFxptZalX53HciENV7SUxvkxA',
    'TELEGRAM_CHAT_ID_ERROR_APP'   => '-613698879',
];
