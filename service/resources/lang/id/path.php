<?php

if (env('APP_ENV') == 'live') {
    return
        [
            'URL_PAY_STORIES'  => 'https://id.monkeyenglish.net/lp1-app?utm_source=App&utm_medium=Free-trial&utm_content=bang-gia',
            'URL_PAY_MONKEYMATH' => 'http://toantienganh.monkeymath.vn/cb03-app/?utm_source=App&utm_medium=App_Mai_MM&utm_campaign=BangGiaAppMM',
            'URL_PAY_VMONKEY' => 'http://truyentiengviet.vmonkey.vn/app?utm_source=App&utm_medium=App_VM&utm_campaign=BangGiaAppVM',
            'URL_PAY_MONKEYJUNIOR' => 'http://beyeungoaingu.monkeyjunior.vn/lp4-app/?utm_source=App&utm_medium=App_Mai_MJ&utm_campaign=BangGiaAppMJ'
        ];
} else {
    return
        [
            'URL_PAY_STORIES'  => 'http://truyentranh.monkeystories.vn/lp5-app/?utm_source=App&utm_medium=App_Mai_MS&utm_campaign=BangGiaAppMS',
            'URL_PAY_MONKEYMATH' => 'http://toantienganh.monkeymath.vn/cb03-app/?utm_source=App&utm_medium=App_Mai_MM&utm_campaign=BangGiaAppMM',
            'URL_PAY_VMONKEY' => 'http://truyentiengviet.vmonkey.vn/app?utm_source=App&utm_medium=App_VM&utm_campaign=BangGiaAppVM',
            'URL_PAY_MONKEYJUNIOR' => 'https://beyeungoaingu.monkeyjunior.vn/test-landing-app?utm_source=App&utm_medium=App_Mai_MJ&utm_campaign=BangGiaAppMJ'
        ];
}

?>
