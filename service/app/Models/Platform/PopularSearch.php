<?php

namespace App\Models\Platform;
use Illuminate\Database\Eloquent\Model;

class PopularSearch extends Model
{
    protected $table      = self::TABLE;
    public    $timestamps = false;

    const TABLE = 'popular_search';
    protected $connection = 'edu_platform';

    const _ID           = 'id';
    const _KEYWORD      = 'keyword';
    const _THUMB        = 'thumb';
    const _USER_ID      = 'user_id';
    const _APP_ID       = 'app_id';
    const _TYPE         = 'type';
    const _SORT         = 'sort';
    const _STATUS       = 'status';
    const _IS_DELETED   = 'is_deleted';
    const _TIME_UPDATE  = 'time_update';
    const _TIME_CRETAED = 'time_created';

    const IS_ACTIVE = 1;

    const POPULAR_STORY = 1;
    const POPULAR_AUDIO = 2;

    const POPULAR_WORKSHEET_PHONIC = 3;
    const POPULAR_WORKSHEET_STORY  = 4;

    const KEY_REDIS_POPULAR_SEARCH = 'KEY_REDIS_POPULAR_SEARCH';

    protected $fillable =
        [
            self::_ID,
            self::_KEYWORD,
            self::_THUMB,
            self::_USER_ID,
            self::_APP_ID,
            self::_TYPE,
            self::_SORT,
            self::_STATUS,
            self::_IS_DELETED,
            self::_TIME_CRETAED,
            self::_TIME_UPDATE
        ];
}
