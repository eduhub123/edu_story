<?php
if (env('APP_ENV') == 'live') {
    return [
        'API_PRODUCT_PAY'  => 'https://www.api.monkeyuni.net',
        'HOME_WEB_SERVICE' => 'https://www.monkeystories.vn/',
    ];
} else {
    return [
        'API_PRODUCT_PAY'  => 'https://api.dev.monkeyuni.net',
        'HOME_WEB_SERVICE' => 'https://dev.monkeystories.vn/',
    ];

}
?>
