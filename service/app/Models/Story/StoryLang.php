<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class StoryLang extends Model
{
    const TABLE = 'story_lang';

    protected $connection = 'edu_story';
    public    $timestamps = false;
    protected $table      = self::TABLE;
    protected $primaryKey = 'slang_id';

    const _SLANG_ID       = 'slang_id';
    const _SID            = 'sid';
    const _LANG_ID        = 'lang_id';
    const _IS_ORIGINAL    = 'is_original';
    const _NAME           = 'name';
    const _NAME_ALIAS     = 'name_alias';
    const _HAVE_LEARNED   = 'have_learned';
    const _DESCRIPTION    = 'description';
    const _ICON           = 'icon';
    const _TRANGBIA       = 'trangbia';
    const _LEVEL          = 'level';
    const _MOSTPUPULAR    = 'mostpupular';
    const _WRITED_TEXT_ID = 'writed_text_id';
    const _DESIGN_TEXT_ID = 'design_text_id';
    const _REVIEW_NB      = 'review_nb';
    const _STATUS         = 'status';
    const _CREATOR_ID     = 'creator_id';
    const _VER            = 'ver';
    const _REVIEW_STEP1   = 'review_step1';
    const _LOCK           = 'lock';
    const _SCORE          = 'score';
    const _QUALITY_SCORE  = 'quality_score';
    const _DATE_PUBLISH   = 'date_publish';
    const _COUNT_TEXTS    = 'count_texts';
    const _VERSION_STORY  = 'version_story';
    const _DATA           = 'data';
    const _API_VER        = 'api_ver';
    const _GO_TO_LIVE     = 'go_to_live';
    const _DELETE         = 'delete';
    const _CREATE_TIME    = 'create_time';
    const _ZIP_SIZE       = 'zip_size';
    const _CONFIG_VER     = 'config_ver';

    const TEST_IN_HOUSE = 1;
    const GO_TO_LIVE    = 99;

    protected $fillable = [
        self::_SLANG_ID,
        self::_SID,
        self::_LANG_ID,
        self::_IS_ORIGINAL,
        self::_NAME,
        self::_NAME_ALIAS,
        self::_HAVE_LEARNED,
        self::_DESCRIPTION,
        self::_ICON,
        self::_TRANGBIA,
        self::_LEVEL,
        self::_MOSTPUPULAR,
        self::_WRITED_TEXT_ID,
        self::_REVIEW_NB,
        self::_STATUS,
        self::_CREATOR_ID,
        self::_VER,
        self::_REVIEW_STEP1,
        self::_LOCK,
        self::_SCORE,
        self::_QUALITY_SCORE,
        self::_DATE_PUBLISH,
        self::_COUNT_TEXTS,
        self::_VERSION_STORY,
        self::_DATA,
        self::_API_VER,
        self::_GO_TO_LIVE,
        self::_DELETE,
        self::_CREATE_TIME,
        self::_ZIP_SIZE,
        self::_CONFIG_VER
    ];


}
