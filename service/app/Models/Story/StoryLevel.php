<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class StoryLevel extends Model
{

    const TABLE = 'story_level';

    protected $connection = 'edu_story';
    protected $table      = self::TABLE;
    public    $timestamps = false;

    const _ID       = 'id';
    const _SLANG_ID = 'slang_id';
    const _LEVEL_ID = 'level_id';

    const ITEM_PAGE = 10;
    const IS_DELETE = 1;

    public function storyLangRelate()
    {
        return $this->belongsTo(StoryLang::class, StoryLang::_SLANG_ID, self::_ID);
    }

}
