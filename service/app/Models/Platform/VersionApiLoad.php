<?php

namespace App\Models\Platform;

use Illuminate\Database\Eloquent\Model;

class VersionApiLoad extends Model
{
    public    $timestamps = false;
    protected $connection = 'edu_platform';
    protected $table      = self::TABLE;

    const TABLE = 'version_api_load';

    const _ID             = 'id';
    const _VERSION_NUMBER = 'version_number';
    const _APP_ID         = 'app_id';
    const _TYPE           = 'type';
    const _FILE_PATH      = 'file_path';
    const _TIME_CREATED   = 'time_created';
    const _TIME_UPDATED   = 'time_updated';

    const TYPE_STORIES_ACTIVITES = 1;
    const TYPE_STORIES_LESSON    = 2;
    const TYPE_AUDIO_BOOK        = 3;
    const TYPE_GAME              = 4;
    const TYPE_CATE              = 5;
    const TYPE_WORKSHEET         = 6;
    const TYPE_LESSON            = 7;
    const TYPE_LESSON_US         = 8;
    const TYPE_LESSON_US_PRIVATE = 11;
    const TYPE_LESSON_US_PROFILE = 12;
    const TYPE_AWARD             = 9;
    const TYPE_WORD              = 10;
    const AUDIOBOOK_APP          = 40;
    const TYPE_FLOW              = 100;
    const TYPE_COMMON_MK_TALKING = 102;


    const TYPE_WORD_LANGUAGE = 'TYPE_WORD_LANGUAGE_';
    const LIST_TYPE_WORD = [
        'TYPE_WORD_LANGUAGE_1'  => 20,
        'TYPE_WORD_LANGUAGE_4'  => 21,
        'TYPE_WORD_LANGUAGE_21' => 22,
        'TYPE_WORD_LANGUAGE_2'  => 23,
        'TYPE_WORD_LANGUAGE_12' => 24,
        'TYPE_WORD_LANGUAGE_8'  => 25,
        'TYPE_WORD_LANGUAGE_7'  => 26,
        'TYPE_WORD_LANGUAGE_9'  => 27,
        'TYPE_WORD_LANGUAGE_10' => 28
    ];

    const TYPE_CATE_LANGUAGE = 'TYPE_CATE_LANGUAGE_';
    const LIST_TYPE_CATE = [
        'TYPE_CATE_LANGUAGE_1'  => 30,
        'TYPE_CATE_LANGUAGE_4'  => 31,
        'TYPE_CATE_LANGUAGE_21' => 32,
        'TYPE_CATE_LANGUAGE_2'  => 33,
        'TYPE_CATE_LANGUAGE_12' => 34,
        'TYPE_CATE_LANGUAGE_8'  => 35,
        'TYPE_CATE_LANGUAGE_7'  => 36,
        'TYPE_CATE_LANGUAGE_9'  => 37,
        'TYPE_CATE_LANGUAGE_10' => 38
    ];

    protected $fillable =
        [
            self::_ID,
            self::_VERSION_NUMBER,
            self::_APP_ID,
            self::_TYPE,
            self::_FILE_PATH,
            self::_TIME_CREATED,
            self::_TIME_UPDATED
        ];
}
