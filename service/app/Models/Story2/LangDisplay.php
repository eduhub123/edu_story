<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class LangDisplay extends Model
{
    public    $timestamps = false;
    protected $connection = 'edu_story_2';
    protected $table      = self::TABLE;
    protected $primaryKey = self::_ID_LANG_DISPLAY;

    const TABLE = 'lang_display';

    const _ID_LANG_DISPLAY = 'id_lang_display';
    const _CODE            = 'code';
    const _LANGUAGE        = 'language';
    const _FLAG            = 'flag';
    const _STATUS          = 'status';
    const _CREATED_AT      = 'created_at';
    const _UPDATED_AT      = 'updated_at';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    protected $fillable = [
        self::_ID_LANG_DISPLAY,
        self::_CODE,
        self::_LANGUAGE,
        self::_FLAG,
        self::_STATUS,
        self::_CREATED_AT,
        self::_UPDATED_AT,
    ];
}

