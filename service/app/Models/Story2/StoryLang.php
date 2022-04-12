<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class StoryLang extends Model
{
    const TABLE = 'story_lang';

    protected $connection   = 'edu_story_2';
    public    $timestamps   = false;
    protected $table        = self::TABLE;
    protected $primaryKey   = 'id_story_lang';

    const _ID_STORY_LANG    = 'id_story_lang';
    const _ID_STORIES       = 'id_stories';
    const _ID_LANGUAGES     = 'id_languages';
    const _APP_ID           = 'app_id';
    const _LEVEL_SYSTEM     = 'level_system';
    const _NAME             = 'name';
    const _DESCRIPTION      = 'description';
    const _ID_TEXT_WRITER   = 'id_text_writer';
    const _ID_TEXT_DESIGNER = 'id_text_designer';
    const _COVERPAGE        = 'coverpage';
    const _THUMB            = 'thumb';
    const _LIKE             = 'like';
    const _STATUS           = 'status';
    const _MOST_POPULAR     = 'most_popular';
    const _REVIEW_STEP1     = 'review_step1';
    const _LOCK             = 'lock';
    const _SCORE            = 'score';
    const _QUALITY_SCORE    = 'quality_score';
    const _VER              = 'ver';
    const _REVIEW_NB        = 'review_nb';
    const _DATE_PUBLISH     = 'date_publish';
    const _COUNT_TEXTS      = 'count_texts';
    const _VERSION_STORY    = 'version_story';
    const _DATA             = 'date';
    const _API_VER          = 'api_ver';
    const _DELETE           = 'delete';
    const _ZIP_SIZE         = 'zip_size';
    const _CONFIG_VER       = 'config_ver';
    const _IS_ORIGINAL      = 'is_original';
    const _NAME_ALIAS       = 'name_alias';
    const _HAVE_LEARNED     = 'have_learned';
    const _ICON             = 'icon';
    const _GO_TO_LIVE       = 'go_to_live';
    const _CREATE_TIME      = 'create_time';
    const _UPDATE_TIME      = 'update_time';

    const TEST_IN_HOUSE = 1;
    const GO_TO_LIVE    = 99;

    protected $fillable = [
        self::_ID_STORY_LANG,
        self::_ID_STORIES,
        self::_ID_LANGUAGES,
        self::_APP_ID,
        self::_LEVEL_SYSTEM,
        self::_NAME,
        self::_DESCRIPTION,
        self::_ID_TEXT_WRITER,
        self::_ID_TEXT_DESIGNER,
        self::_COVERPAGE,
        self::_THUMB,
        self::_LIKE,
        self::_STATUS,
        self::_MOST_POPULAR,
        self::_REVIEW_STEP1,
        self::_LOCK,
        self::_SCORE,
        self::_QUALITY_SCORE,
        self::_VER,
        self::_REVIEW_NB,
        self::_DATE_PUBLISH,
        self::_COUNT_TEXTS,
        self::_VERSION_STORY,
        self::_DATA,
        self::_API_VER,
        self::_DELETE,
        self::_ZIP_SIZE,
        self::_CONFIG_VER,
        self::_IS_ORIGINAL,
        self::_NAME_ALIAS,
        self::_HAVE_LEARNED,
        self::_ICON,
        self::_CREATE_TIME,
        self::_UPDATE_TIME,
    ];
}