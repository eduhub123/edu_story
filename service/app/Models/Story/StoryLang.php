<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class StoryLang extends Model
{
    const TABLE = 'story_lang';

    protected $connection = 'edu_story';
    protected $table      = self::TABLE;
    public    $timestamps = false;

    const _SLANG_ID      = 'slang_id';
    const _SID           = 'sid';
    const _LANG_ID       = 'lang_id';
    const _QUALITY_SCORE = 'quality_score';
    const _ICON          = 'icon';
    const _DATA          = 'data';
    const _ZIP_SIZE      = 'zip_size';
    const _DELETE        = 'delete';
    const _API_VER       = 'api_ver';
    const _VERSION_STORY = 'version_story';
    const _DATE_PUBLISH  = 'date_publish';
    const _STATUS        = 'status';

    const TEST_IN_HOUSE = 1;
    const GO_TO_LIVE    = 99;

    protected $primaryKey = self::_SLANG_ID;

    protected $fillable = [
        self::_ZIP_SIZE
    ];


}
