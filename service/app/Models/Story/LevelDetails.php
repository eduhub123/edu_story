<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class LevelDetails extends Model
{
    const TABLE = 'level_details';

    protected $connection = 'edu_story';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID             = 'id';
    const _TEXT           = 'text';
    const _LANG_ID        = 'lang_id';
    const _LEVEL          = 'level';
    const _DESCRIPTION    = 'description';
    const _GRADE_ID       = 'grade_id';
    const _GROUP_LEVEL_ID = 'group_level_id';


    protected $fillable = [
        self::_ID,
        self::_TEXT,
        self::_LANG_ID,
        self::_LEVEL,
        self::_DESCRIPTION,
        self::_GRADE_ID,
        self::_GROUP_LEVEL_ID
    ];


}
