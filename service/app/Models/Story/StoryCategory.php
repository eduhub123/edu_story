<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class StoryCategory extends Model
{
    const TABLE = 'story_category';

    protected $connection = 'edu_story';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID       = 'id';
    const _SLANG_ID = 'slang_id';
    const _CATE_ID  = 'cateId';


    protected $fillable = [
        self::_ID,
        self::_SLANG_ID,
        self::_CATE_ID
    ];


}
