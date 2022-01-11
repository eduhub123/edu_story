<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class FreeStory  extends Model
{
    protected $table = 'free_story';

    protected $connection = 'edu_story';

    protected $fillable =
        [
            'id',
            'year',
            'month',
            'days',
            'slang_id'
        ];


    public function story_lang_relate()
    {
        return $this->belongsTo('\App\Models\Story\StoryLang', 'slang_id', 'slang_id');
    }
}
