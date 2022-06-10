<?php

namespace App\Models;

use App\Models\Globals\ListApp;

class LangDisplay
{
    const ID_LANG_DISPLAY_EN = 1;
    const ID_LANG_DISPLAY_VN = 4;
    const ID_LANG_DISPLAY_TH = 5;
    const ID_LANG_DISPLAY_ID = 6;

    const LIST_LANG_DISPLAY = [
        self::ID_LANG_DISPLAY_EN,
        self::ID_LANG_DISPLAY_VN,
        self::ID_LANG_DISPLAY_TH,
        self::ID_LANG_DISPLAY_ID
    ];

    const CODE_LANG_DISPLAY_VN = "vi-VN";
    const CODE_LANG_DISPLAY_US = "en-US";
    const CODE_LANG_DISPLAY_TH = "th-TH";
    const CODE_LANG_DISPLAY_ID = "id-ID";

    const LIST_LANG_DISPLAY_APP = [
        "vn" => [
            'code' => self::CODE_LANG_DISPLAY_VN,
            'name' => [
                self::CODE_LANG_DISPLAY_VN => 'Tiếng Việt',
                self::CODE_LANG_DISPLAY_US => 'Tiếng Anh',
                self::CODE_LANG_DISPLAY_TH => "Tiếng Thái"
            ]
        ],
        "us" => [
            'code' => self::CODE_LANG_DISPLAY_US,
            'name' => [
                self::CODE_LANG_DISPLAY_VN => 'Vietnamese',
                self::CODE_LANG_DISPLAY_US => 'English',
                self::CODE_LANG_DISPLAY_TH => "Thailand"
            ]
        ],
        "th" => [
            'code' => self::CODE_LANG_DISPLAY_TH,
            'name' => [
                self::CODE_LANG_DISPLAY_VN => 'เวียตนาม',
                self::CODE_LANG_DISPLAY_US => 'อังกฤษ',
                self::CODE_LANG_DISPLAY_TH => 'ชาวไทย'
            ]
        ],
        "id" => [
            'code' => self::CODE_LANG_DISPLAY_ID,
            'name' => [
                self::CODE_LANG_DISPLAY_VN => 'Vietnamese',
                self::CODE_LANG_DISPLAY_US => 'English',
                self::CODE_LANG_DISPLAY_TH => "Thailand"
            ]
        ],
    ];

    public static function getIdLangDisplayByIdApp($idApp)
    {
        $idLang = self::ID_LANG_DISPLAY_VN;
        if ($idApp == ListApp::APP_ID_MS_EN) {
            $idLang = self::ID_LANG_DISPLAY_EN;
        }
        return $idLang;
    }
}
