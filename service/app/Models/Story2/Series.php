<?php


namespace App\Models\Story2;


use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    public    $timestamps = false;
    protected $connection = 'edu_story_2';
    protected $table      = self::TABLE;
    protected $primaryKey = self::_ID_SERIES;

    const TABLE = 'series';

    const _ID_SERIES       = 'id_series';
    const _ID_APP          = 'id_app';
    const _THUMB           = 'thumb';
    const _KEY_NAME        = 'key_name';
    const _KEY_DESCRIPTION = 'key_description';
    const _ORDER           = 'order';
    const _STATUS          = 'status';
    const _CREATED_AT      = 'created_at';
    const _UPDATED_AT      = 'updated_at';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    const NOT_HIDDEN = 0;
    const HIDDEN = 1;

    const PATH_UPLOAD_THUMB =  "upload/cms/series";

    protected $fillable = [
        self::_ID_SERIES,
        self::_ID_APP,
        self::_THUMB,
        self::_KEY_NAME,
        self::_KEY_DESCRIPTION,
        self::_ORDER,
        self::_STATUS,
        self::_CREATED_AT,
        self::_UPDATED_AT,
    ];

    public function name()
    {
        return $this->hasMany(Translate::class, Translate::_KEY, self::_KEY_NAME)->keyBy(Translate::_ID_LANG_DISPLAY);
    }

    public static function convertStatusToHidden($status)
    {
        $hidden = self::NOT_HIDDEN;
        if ($status == self::STATUS_INACTIVE) {
            $hidden = self::HIDDEN;
        }
        return $hidden;
    }
}
