<?php

namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    const TABLE = 'grade';

    protected $connection = 'edu_story_2';
    public    $timestamps = false;
    protected $table      = self::TABLE;

    const _ID_GRADE        = 'id_grade';
    const _ID_APP          = 'id_app';
    const _KEY_NAME        = 'key_name';
    const _KEY_DESCRIPTION = 'key_description';
    const _GROUP           = 'group';
    const _STATUS          = 'status';
    const _CREATED_AT      = 'created_at';
    const _UPDATED_AT      = 'updated_at';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    protected $fillable = [
        self::_ID_GRADE,
        self::_ID_APP,
        self::_KEY_NAME,
        self::_KEY_DESCRIPTION,
        self::_GROUP,
        self::_STATUS,
        self::_CREATED_AT,
        self::_UPDATED_AT,
    ];

    public function name()
    {
        return $this->hasMany(Translate::class, Translate::_KEY, self::_KEY_NAME);
    }

    public function description()
    {
        return $this->hasMany(Translate::class, Translate::_KEY, self::_KEY_DESCRIPTION);
    }
}
