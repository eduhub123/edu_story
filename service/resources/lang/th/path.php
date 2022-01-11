<?php

if (env('APP_ENV') == 'live') {
    return
        [
            'URL_PAY_STORIES'  => 'https://th.monkeyenglish.net/th_app?utm_source=App&utm_medium=inApp&utm_content=inApp',
            'URL_PAY_MONKEYMATH' => 'https://th.monkeyenglish.net/monkeymath?utm_source=App&utm_medium=inApp&utm_content=free_popup',
            'URL_PAY_VMONKEY' => 'http://truyentiengviet.vmonkey.vn/app?utm_source=App&utm_medium=App_VM&utm_campaign=BangGiaAppVM',
            'URL_PAY_MONKEYJUNIOR' => 'https://th.monkeyenglish.net/dungnqmj1?utm_source=App&utm_medium=App_Mai_MJ&utm_campaign=BangGiaAppMJ'
        ];
} else {
    return
        [
            'URL_PAY_STORIES'  => 'https://th.monkeyenglish.net/th_app?utm_source=App&utm_medium=inApp&utm_content=inApp',
            'URL_PAY_MONKEYMATH' => 'https://th.monkeyenglish.net/monkeymath?utm_source=App&utm_medium=inApp&utm_content=free_popup',
            'URL_PAY_VMONKEY' => 'http://truyentiengviet.vmonkey.vn/app?utm_source=App&utm_medium=App_VM&utm_campaign=BangGiaAppVM',
            'URL_PAY_MONKEYJUNIOR' => 'https://th.monkeyenglish.net/dungnqmj1?utm_source=App&utm_medium=App_Mai_MJ&utm_campaign=BangGiaAppMJ'
        ];
}

?>
