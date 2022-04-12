<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class StoryLevel extends Model
{
    const TABLE = 'story_level';

    protected $connection = 'edu_story_2';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID            = 'id';
    const _ID_LEVELS     = 'id_levels';
    const _ID_STORY_LANG = 'id_story_lang';


    protected $fillable = [
        self::_ID,
        self::_ID_LEVELS,
        self::_ID_STORY_LANG
    ];

    const ITEM_PAGE = 10;
    const IS_DELETE = 1;

    public function storyLangRelate()
    {
        return $this->belongsTo(StoryLang::class, StoryLang::_ID_STORY_LANG, self::_ID);
    }
}