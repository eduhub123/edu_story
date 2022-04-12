<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    const TABLE = 'level';

    protected $connection = 'edu_story_2';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID_LEVEL        = 'id_level';
    const _ID_GRADE        = 'id_grade';
    const _KEY_NAME        = 'key_name';
    const _KEY_DESCRIPTION = 'key_description';
    const _STATUS          = 'status';


    protected $fillable = [
        self::_ID_LEVEL,
        self::_ID_GRADE,
        self::_KEY_NAME,
        self::_KEY_DESCRIPTION,
        self::_STATUS
    ];
}