<?php

namespace App\Models;

use App\Models\Globals\ListApp;

class Language
{
    const ID_LANG_EN = 1;
    const ID_LANG_VN = 4;
    const ID_LANG_TH = 5;
    const ID_LANG_ID = 6;

    const CODE_LANG_EN = "en";
    const CODE_LANG_VN = "vn";
    const CODE_LANG_TH = "th";
    const CODE_LANG_ID = "id";

    public static function getIdLanguageByIdApp($idApp)
    {
        $idLang = self::ID_LANG_VN;
        if ($idApp == ListApp::APP_ID_MS_EN) {
            $idLang = self::ID_LANG_EN;
        }
        return $idLang;
    }
}
