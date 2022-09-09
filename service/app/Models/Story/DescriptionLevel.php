<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class DescriptionLevel extends Model
{

    public    $timestamps = false;
    protected $connection = 'edu_story';
    protected $table      = self::TABLE;

    const TABLE = 'description_level_display';

    const _ID              = 'id';
    const _DESCRIPTION     = 'description';
    const _LEVEL_ORDER     = 'level_order';
    const _LANG_DISPLAY_ID = 'lang_display_id';

    protected $fillable = [
        self::_ID,
        self::_DESCRIPTION,
        self::_LEVEL_ORDER,
        self::_LANG_DISPLAY_ID
    ];

}
