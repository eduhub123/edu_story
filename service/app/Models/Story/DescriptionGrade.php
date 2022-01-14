<?php

namespace App\Models\Story;

use Illuminate\Database\Eloquent\Model;

class DescriptionGrade extends Model
{
    const TABLE = 'description_grade';

    protected $connection = 'edu_story';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID           = 'id';
    const _DES          = 'des';
    const _GRADE_ID     = 'grade_id';
    const _LANG_DISPLAY = 'lang_display';

    protected $fillable = [
        self::_ID,
        self::_DES,
        self::_GRADE_ID,
        self::_LANG_DISPLAY
    ];


}
