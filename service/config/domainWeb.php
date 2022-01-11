<?php
if (env('APP_ENV') == 'live') {
    return 
    [
        'redirect_uri'   => 'https://api.monkeyuni.net/api/redirect-uri?uri='
    ];
} else {
    return 
    [
        'redirect_uri'   => 'https://api.dev.monkeyuni.net/api/redirect-uri?uri='
    ];
}
?>
