<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class FreeStory extends Model
{
    const TABLE = 'free_story';

    protected $connection = 'edu_story';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID       = 'id';
    const _YEAR     = 'year';
    const _MONTH    = 'month';
    const _DAYS     = 'days';
    const _SLANG_ID = 'slang_id';

    const IS_ACTIVE = 1;

    protected $fillable = [
        self::_ID,
        self::_YEAR,
        self::_MONTH,
        self::_DAYS,
        self::_SLANG_ID
    ];


    public function story_lang_relate()
    {
        return $this->belongsTo(StoryLang::class, StoryLang::_SLANG_ID, self::_SLANG_ID);
    }
}
