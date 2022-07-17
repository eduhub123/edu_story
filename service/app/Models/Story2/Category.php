<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $connection = 'edu_story_2';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const TABLE = 'category';

    const _ID_CATEGORY = 'id_category';
    const _ID_APP      = 'id_app';
    const _KEY_NAME    = 'key_name';
    const _IMAGE       = 'image';
    const _ORDER_CATE  = 'order_cate';
    const _CHECK_CATE  = 'check_cate';
    const _STATUS      = 'status';
    const _CREATED_AT  = 'created_at';
    const _UPDATED_AT  = 'updated_at';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    const PATH_UPLOAD_IMAGE_CATEGORY = "upload/cms/category";

    protected $fillable = [
        self::_ID_CATEGORY,
        self::_ID_APP,
        self::_KEY_NAME,
        self::_IMAGE,
        self::_ORDER_CATE,
        self::_CHECK_CATE,
        self::_STATUS,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];


}
