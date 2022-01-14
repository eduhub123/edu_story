<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class DescriptionLevel extends Model
{
    const TABLE = 'description_level_display';

    protected $connection = 'edu_story';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID           = 'id';
    const _DESCRIPTION  = 'description';
    const _LEVEL_ORDER  = 'level_order';
    const _LANG_DISPLAY = 'lang_display_id';

    const IS_ACTIVE = 1;

    protected $fillable = [
        self::_ID,
        self::_DESCRIPTION,
        self::_LEVEL_ORDER,
        self::_LANG_DISPLAY
    ];


}
