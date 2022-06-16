<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class StoryLangCategory extends Model
{

    protected $connection = 'edu_story_2';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const TABLE = 'story_category';

    const _ID_STORY_LANG = 'id_story_lang';
    const _ID_CATEGORY   = 'id_category';

    protected $fillable = [
        self::_ID_STORY_LANG,
        self::_ID_CATEGORY,
    ];

}
