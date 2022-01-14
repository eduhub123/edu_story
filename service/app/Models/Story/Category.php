<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const TABLE = 'category';

    protected $connection = 'edu_story';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID         = 'id';
    const _CONTENT    = 'content';
    const _IMAGE      = 'image';
    const _ORDER_CATE = 'order_cate';
    const _CHECK_CATE = 'check_cate';
    const _LANG_ID    = 'lang_id';

    const IS_ACTIVE = 1;

    protected $fillable = [
        self::_ID,
        self::_CONTENT,
        self::_IMAGE,
        self::_ORDER_CATE,
        self::_CHECK_CATE,
        self::_LANG_ID
    ];


}
