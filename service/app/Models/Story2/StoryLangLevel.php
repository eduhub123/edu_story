<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class StoryLangLevel extends Model
{

    protected $connection = 'edu_story_2';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const TABLE = 'story_level';

    const _ID_STORY_LANG = 'id_story_lang';
    const _ID_LEVEL      = 'id_level';

    protected $fillable = [
        self::_ID_STORY_LANG,
        self::_ID_LEVEL
    ];

    public function level()
    {
        return $this->hasOne(Level::class, Level::_ID_LEVEL, self::_ID_LEVEL);
    }

}
