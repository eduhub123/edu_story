<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class WorksheetAssignLesson extends Model
{

    public    $timestamps = false;
    protected $connection = 'edu_story_2';
    protected $table      = self::TABLE;

    const TABLE = 'courseware_assign_lesson';

    const _ID            = 'id';
    const _ID_COURSEWARE = 'id_courseware';
    const _LESSON_ID     = 'lesson_id';
    const _CREATED_AT    = 'created_at';
    const _IS_DELETED    = 'is_deleted';

    protected $fillable =
        [
            self::_ID,
            self::_ID_COURSEWARE,
            self::_LESSON_ID,
            self::_IS_DELETED,
            self::_CREATED_AT
        ];
}
