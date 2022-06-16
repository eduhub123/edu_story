<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{

    protected $connection = 'edu_story_2';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const TABLE = 'level';

    const _ID_LEVEL        = 'id_level';
    const _ID_APP          = 'id_app';
    const _ID_GRADE        = 'id_grade';
    const _LEVEL           = 'level';
    const _KEY_NAME        = 'key_name';
    const _KEY_DESCRIPTION = 'key_description';
    const _STATUS          = 'status';
    const _CREATED_AT      = 'created_at';
    const _UPDATED_AT      = 'updated_at';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    protected $fillable = [
        self::_ID_LEVEL,
        self::_ID_APP,
        self::_ID_GRADE,
        self::_LEVEL,
        self::_KEY_NAME,
        self::_KEY_DESCRIPTION,
        self::_STATUS,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];


}
