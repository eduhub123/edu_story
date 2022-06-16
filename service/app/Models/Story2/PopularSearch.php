<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class PopularSearch extends Model
{
    public    $timestamps = false;
    protected $connection = 'edu_story_2';
    protected $table      = self::TABLE;

    const TABLE = 'popular_search';

    const _ID           = 'id';
    const _KEYWORD      = 'keyword';
    const _THUMB        = 'thumb';
    const _ID_USER      = 'user_id';
    const _ID_APP       = 'app_id';
    const _TYPE         = 'type';
    const _SORT         = 'sort';
    const _STATUS       = 'status';
    const _IS_DELETED   = 'is_deleted';
    const _CREATED_AT   = 'created_at';
    const _UPDATED_AT   = 'updated_at';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    const POPULAR_STORY            = 1;
    const POPULAR_AUDIO            = 2;
    const POPULAR_WORKSHEET_PHONIC = 3;
    const POPULAR_WORKSHEET_STORY  = 4;

    protected $fillable =
        [
            self::_ID,
            self::_KEYWORD,
            self::_THUMB,
            self::_ID_USER,
            self::_ID_APP,
            self::_TYPE,
            self::_SORT,
            self::_STATUS,
            self::_IS_DELETED,
            self::_CREATED_AT,
            self::_UPDATED_AT
        ];
}
