<?php

namespace App\Services\Globals;

use App\Services\RedisService;
use App\Services\ServiceConnect\CrmConnectService;

class ProductService
{

    private $crmConnectService;
    private $redisService;

    const KEY_REDIS_LIST_PAY_PRODUCT = "LIST_PAY_PRODUCT_";

    public function __construct(
        CrmConnectService $crmConnectService,
        RedisService $redisService
    ) {
        $this->crmConnectService = $crmConnectService;
        $this->redisService      = $redisService;
    }

    public function listProductShowOnApp()
    {
        return $productList =
            [
                'com.earlystart.stories.1month',
                'com.earlystart.stories.6month',
                'com.earlystart.stories.1year',
                'com.earlystart.stories.lifetime',
                'com.earlystart.math.1month',
                'com.earlystart.math.6month',
                'com.earlystart.math.1year',
                'com.earlystart.math.lifetime',
                'com.earlystart.stories.vn.1month',
                'com.earlystart.stories.vn.6month',
                'com.earlystart.stories.vn.1year',
                'com.earlystart.stories.vn.lifetime',
                'com.earlystart.ltr.1month',
                'com.earlystart.ltr.6month',
                'com.earlystart.ltr.1year',
                'com.earlystart.alllanguage'
            ];
    }

    public function getDataListPay($appId, $countryCode)
    {
        $keyRedis = self::KEY_REDIS_LIST_PAY_PRODUCT . "_" . $appId . "_" . $countryCode;
        $listPay  = $this->redisService->get($keyRedis, true);
        if (!$listPay) {
            $listPay = $this->crmConnectService->getListPay($appId, $countryCode);
            if ($listPay) {
                $this->redisService->set($keyRedis, json_encode($listPay, true));
            }
        }
        return $listPay;
    }

    public function listProductByApp($appId)
    {
        $data            = [];
        $listCountryCode = $this->getListCountryCode();
        foreach ($listCountryCode as $value) {
            $countryName        = $value['country_name'];
            $countryCode        = $value['country_code'];
            $currency           = $value['currency'];
            $listPay            = $this->getDataListPay($appId, $countryCode);
            $listPayNew         = $this->getInfoPackage($listPay, $currency);
            $data[$countryName] = $listPayNew;
        }
        return $data;
    }

    private function getInfoPackage($listPay, $currency)
    {
        $arrNewProduct = [];
        foreach ($listPay as $product) {
            if (!in_array($product['product_id'], $this->listProductShowOnApp())) {
                continue;
            }
            $priceOrigin = $product['price'][1]['origin'] ?? 0;
            $priceSale   = $product['price'][1]['saleOf'] ?? 0;
            $saleOff     = $product['price'][1]['saleoff'] ?? 0;

            $item['tienao']              = $priceOrigin;
            $item['giam40']              = $priceSale;
            $item['giam30']              = 0;
            $item['product_id']          = $product['product_id'];
            $item['product_name']        = $product['product_name'];
            $item['product_description'] = $product['product_description'];
            $item['product_code']        = '';
            $item['saleoff']             = $saleOff;
            $item['currency']            = $currency;

            $arrNewProduct[] = $item;
        }
        return $arrNewProduct;
    }

    public function getListCountryCode()
    {
        return $listCountryCode = [
            [
                'country_name' => 'VN',
                'country_code' => 84,
                'currency'     => "đ",
            ],
            [
                'country_name' => 'TH',
                'country_code' => 66,
                'currency'     => "THB",
            ],
            [
                'country_name' => 'ID',
                'country_code' => 62,
                'currency'     => "Rp",
            ],
            [
                'country_name' => 'US',
                'country_code' => 1,
                'currency'     => "$",
            ]
        ];
    }
}
