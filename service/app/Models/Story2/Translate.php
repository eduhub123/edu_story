<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    public    $timestamps = false;
    protected $connection = 'edu_story_2';
    protected $table      = self::TABLE;
    protected $primaryKey = self::_ID_TRANSLATE;

    const TABLE = 'translate';

    const _ID_TRANSLATE    = 'id_translate';
    const _KEY             = 'key';
    const _VALUE           = 'value';
    const _ID_LANG_DISPLAY = 'id_lang_display';
    const _CREATED_AT      = 'created_at';
    const _UPDATED_AT      = 'updated_at';


    protected $fillable = [
        self::_ID_TRANSLATE,
        self::_KEY,
        self::_VALUE,
        self::_ID_LANG_DISPLAY,
        self::_CREATED_AT,
        self::_UPDATED_AT,
    ];

}
