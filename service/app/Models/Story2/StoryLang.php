<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class StoryLang extends Model
{

    public    $timestamps = false;
    protected $connection = 'edu_story_2';
    protected $table      = self::TABLE;
    protected $primaryKey = self::_ID_STORY_LANG;

    const TABLE = 'story_lang';


    const _ID_STORY_LANG = 'id_story_lang';
    const _ID_STORIES    = 'id_stories';
    const _ID_APP        = 'app_id';
    const _ID_LANGUAGES  = 'id_languages';
    const _LEVEL_SYSTEM  = 'level_system';

    const _THUMB           = 'thumb';
    const _NAME           = 'name';
    const _DESCRIPTION    = 'description';
    const _DATA           = 'data';

    const _QUALITY_SCORE  = 'quality_score';
    const _ZIP_SIZE       = 'zip_size';

    const _DELETE         = 'delete';
    const _DATE_PUBLISH   = 'date_publish';

    const _PATH_ZIP_FILE  = 'path_zip_file';
    const _VERSION_STORY  = 'version_story';
    const _API_VER        = 'api_ver';
    const _VER            = 'ver';
    const _STATUS         = 'status';


    const _NAME_ALIAS     = 'name_alias';
    const _HAVE_LEARNED   = 'have_learned';
    const _TRANGBIA       = 'trangbia';
    const _LEVEL          = 'level';
    const _MOSTPUPULAR    = 'mostpupular';
    const _WRITED_TEXT_ID = 'writed_text_id';
    const _DESIGN_TEXT_ID = 'design_text_id';
    const _REVIEW_NB      = 'review_nb';
    const _CREATOR_ID     = 'creator_id';
    const _REVIEW_STEP1   = 'review_step1';
    const _LOCK           = 'lock';
    const _SCORE          = 'score';
    const _COUNT_TEXTS    = 'count_texts';
    const _GO_TO_LIVE     = 'go_to_live';
    const _CREATE_TIME    = 'create_time';
    const _CONFIG_VER     = 'config_ver';

    const TEST_IN_HOUSE = 1;
    const GO_TO_LIVE    = 99;

    const STATUS_INACTIVE = 2;
    const STATUS_ACTIVE   = 1;


    const PATH_UPLOAD_THUMB_HD        = "upload/cms/story/thumb/hd";
    const PATH_UPLOAD_THUMB_HDR       = "upload/cms/story/thumb/hdr";
    const PATH_UPLOAD_COVER_IMAGE_HD  = "upload/cms/story/cover_image/hd";
    const PATH_UPLOAD_COVER_IMAGE_HDR = "upload/cms/story/cover_image/hdr";
    const PATH_UPLOAD_ZIP_HD          = "upload/cms/story/zip/hd";
    const PATH_UPLOAD_ZIP_HDR         = "upload/cms/story/zip/hdr";

    protected $fillable = [
        self::_ID_STORY_LANG,
        self::_ID_STORIES,
        self::_ID_APP,
        self::_ID_LANGUAGES,
        self::_LEVEL_SYSTEM,


        self::_NAME,
        self::_NAME_ALIAS,
        self::_HAVE_LEARNED,
        self::_DESCRIPTION,
        self::_THUMB,
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

    public function story_lang_level()
    {
        return $this->hasMany(StoryLangLevel::class, StoryLangLevel::_ID_STORY_LANG, self::_ID_STORY_LANG);
    }

    public function story_lang_category()
    {
        return $this->hasMany(StoryLangCategory::class, StoryLangCategory::_ID_STORY_LANG, self::_ID_STORY_LANG);
    }
}
