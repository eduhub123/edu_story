<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class Worksheet extends Model
{

    public    $timestamps = false;
    protected $connection = 'edu_story_2';
    protected $table      = self::TABLE;

    const TABLE = 'courseware';

    const _ID             = 'id';
    const _ID_USER        = 'user_id';
    const _NAME_ORIGINAL  = 'name_original';
    const _PATH           = 'path';
    const _ID_APP         = 'app_id';
    const _TYPE           = 'type';
    const _SIZE           = 'size';
    const _VERSION_NUMBER = 'version_number';
    const _IS_ASSIGN      = 'is_assign';
    const _PLATFORM       = 'platform';
    const _IS_PUBLISH     = 'is_publish';
    const _IS_DELETED     = 'is_deleted';
    const _TIME_CREATED   = 'time_created';
    const _TIME_UPDATE    = 'time_updated';

    const IS_ASSIGN      = 1;
    const IS_NOT_ASSIGN  = 0;
    const IS_PUBLISH     = 1;
    const IS_NOT_PUBLISH = 0;
    const IS_DELETED     = 1;

    const TYPE_STORY    = 1;
    const TYPE_AUDIO    = 2;
    const TYPE_PHONIC   = 3;
    const TYPE_DOC_HIEU = 4;

    const PLATFORM_APP = 1;
    const PLATFORM_WEB = 2;
    const PLATFORM_ALL = 3;

    const FOLDER_SAVE         = 'upload/courseware';

    const MAX_WORKSHEET_USER_FREE     = 5;
    const MAX_WORKSHEET_USER_PURCHASE = 7;

    const CONFIG_SEND = [
        'user_free'     => self::MAX_WORKSHEET_USER_FREE,
        'user_purchase' => self::MAX_WORKSHEET_USER_PURCHASE
    ];

    const LIST_ID_PHONIC = [479, 480, 481, 764];

    protected $fillable =
        [
            self::_ID,
            self::_ID_USER,
            self::_NAME_ORIGINAL,
            self::_PATH,
            self::_ID_APP,
            self::_TYPE,
            self::_SIZE,
            self::_VERSION_NUMBER,
            self::_IS_ASSIGN,
            self::_PLATFORM,
            self::_IS_PUBLISH,
            self::_IS_DELETED,
            self::_TIME_CREATED,
            self::_TIME_UPDATE
        ];
}
