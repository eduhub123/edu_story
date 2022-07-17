<?php


namespace App\Models\Story2;

use Illuminate\Database\Eloquent\Model;

class AudioBook extends Model
{
    public    $timestamps = false;
    protected $connection = 'edu_story_2';
    protected $table      = self::TABLE;
    protected $primaryKey = self::_ID_AUDIO_BOOK;

    const TABLE = 'audio_book';

    const _ID_AUDIO_BOOK = 'id_audio_book';
    const _ID_APP        = 'id_app';
    const _ID_LANGUAGE   = 'id_language';
    const _ID_PARENT     = 'id_parent';

    const _ID_SERIES    = 'id_series';
    const _ID_GRADE     = 'id_grade';
    const _LEVEL_SYSTEM = 'level_system';

    const _TITLE       = 'title';
    const _DESCRIPTION = 'description';
    const _CONTENT     = 'content';
    const _EXTRA       = 'extra';

    const _THUMB      = 'thumb';
    const _AUDIO      = 'audio';
    const _DURATION   = 'duration';
    const _AUDIO_SIZE = 'audio_size';
    const _LIKE       = 'like';

    const _DATE_PUBLISH = 'date_publish';
    const _VERSION      = 'version';
    const _CREATED_AT   = 'created_at';
    const _UPDATED_AT   = 'updated_at';


    const PATH_UPLOAD_THUMB = "upload/cms/audio_book/thumb";
    const PATH_UPLOAD_AUDIO = "upload/cms/audio_book/audio";

    protected $fillable = [
        self::_ID_AUDIO_BOOK,
        self::_ID_APP,
        self::_ID_LANGUAGE,
        self::_ID_PARENT,
        self::_ID_SERIES,
        self::_ID_GRADE,
        self::_LEVEL_SYSTEM,
        self::_TITLE,
        self::_DESCRIPTION,
        self::_CONTENT,
        self::_EXTRA,
        self::_THUMB,
        self::_AUDIO,
        self::_DURATION,
        self::_AUDIO_SIZE,
        self::_EXTRA,
        self::_LIKE,
        self::_VERSION,
        self::_CREATED_AT,
        self::_UPDATED_AT,
    ];


    //Relationship

    /**
     * Get the audiobook variants that owns the audiobook.
     */
    public function child()
    {
        return $this->hasMany(self::class, self::_ID_PARENT);
    }

    public function isChildren()
    {
        return !empty($this->parent_id);
    }
}
