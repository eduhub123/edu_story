<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class StoryLang extends Model
{
    protected $table      = 'story_lang';

    public $timestamps = false;

    CONST TEST_IN_HOUSE     = 1;
    CONST GO_TO_LIVE        = 99;

    protected $connection = 'edu_story';
    protected $primaryKey = 'slang_id';

    protected $fillable   = [
        'zip_size'
    ];



}
