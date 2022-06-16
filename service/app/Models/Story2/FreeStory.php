<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class FreeStory extends Model
{
    const TABLE = 'free_story';

    protected $connection = 'edu_story_2';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID_FREE_STORY = 'id_free_story';
    const _ID_APP        = 'id_app';
    const _USER_ID       = 'user_id';
    const _TIME_FREE     = 'time_free';
    const _ID_STORY      = 'id_story';
    const _TYPE          = 'type';
    const _STATUS        = 'status';
    const _CREATED_AT    = 'created_at';
    const _UPDATED_AT    = 'updated_at';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    const TYPE_STORY   = 1;
    const TYPE_AUDIO   = 2;

    protected $fillable = [
        self::_ID_FREE_STORY,
        self::_ID_APP,
        self::_USER_ID,
        self::_TIME_FREE,
        self::_ID_STORY,
        self::_TYPE,
        self::_STATUS,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];


//    public function story_lang_relate()
//    {
//        return $this->belongsTo(StoryLang::class, StoryLang::_SLANG_ID, self::_SLANG_ID);
//    }
}
