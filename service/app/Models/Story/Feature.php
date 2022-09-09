<?php

namespace App\Models\Story;

class Feature
{
    public    $timestamps = false;
    protected $connection = 'edu_story';
    protected $table      = self::TABLE;
    protected $primaryKey = self::_ID;

    const TABLE = 'tbl_feature';

    const _ID        = 'id';
    const _SLANG_ID  = 'slang_id';
    const _IMAGE     = 'image';
    const _ORDER_FEA = 'order_fea';
    const _CHECK_FEA = 'check_fea';

    protected $fillable = [
        self::_ID,
        self::_SLANG_ID,
        self::_IMAGE,
        self::_ORDER_FEA,
        self::_CHECK_FEA,
    ];
}
