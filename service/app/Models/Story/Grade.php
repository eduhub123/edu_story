<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    const TABLE = 'grade';

    protected $connection = 'edu_story';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID    = 'id';
    const _NAME  = 'name';
    const _GROUP = 'group';

    protected $fillable = [
        self::_ID,
        self::_NAME,
        self::_GROUP
    ];


}
