<?php
$arrPath =
    [
        'PATH_SYNC' => '/home/data/platformv1',

        'PATH_DISPLAY' => 'http://datav2.daybehoc.com/platform',

        'PATH_UPLOAD' => '/var/www/data/platform',

        'PATH_SERVER_DEV' => 'http://apidev.daybehoc.com',

        'PATH_SERVER_LIVE' => 'https://apiv2.monkeyuni.net',

        'URL_FIRST_CLUSTER' => 'https://www.monkeyuni.net',

        'DOMAIN_PATH' => 'http://data.daybehoc.com',

        'PATH_PRICE_PROMOTION' => 'upload/price_promotion/price_promotion.json',

        'PATH_PRICE_PROMOTION_TMP' => 'uploads/price_promotion/price_promotion.json',

        'PATH_GROUPS_SALE_OPERATE' => '/config/groups_sale_operate.json',

        'PATH_ADMIN_TELE' => '/config/admin_tele_marketer.json',

        'PATH_ARPL8' => '/config/arpl8.json',

        'PATH_DISCOUNT_PRICE' => '/config/discount_price.json',

        'PATH_L8_DIVISION_L3' => '/config/l8_division_l3.json'
    ];

if (env('APP_ENV') == 'live') {
    $arrPath['PATH_API_CRM'] = 'https://crmv2.monkeyuni.net/api/';

    $arrPath['PATH_DOWNLOAD'] = 'https://datav2.dev.daybehoc.com/platform-dev';
} else {
    $arrPath['PATH_API_CRM'] = 'https://crm.dev.monkeyuni.net/api/';

    $arrPath['PATH_DOWNLOAD'] = 'https://datav2.dev.daybehoc.com/platform-dev';
}
return $arrPath;
?>
