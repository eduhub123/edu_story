<?php


namespace App\Repositories\Story2;

use App\Models\Story2\AudioBook;
use App\Models\Story2\LevelSystem;
use App\Models\Story2\Series;
use App\Models\Story2\StoryLang;
use App\Repositories\EloquentRepository;

class AudioBookRepository extends EloquentRepository
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return AudioBook::class;
    }

    public function getLastVersionAudioBook($idApp, $idLanguage)
    {
        return $maxVersion = $this->_model
            ->where(AudioBook::_ID_APP, $idApp)
            ->where(AudioBook::_ID_LANGUAGE, $idLanguage)
            ->max(AudioBook::_VERSION);
    }

    public function getAudioBooks($idApp, $idLanguage, $version, $limit = null, $offset = null)
    {
        $query = $this->_model->select(
            AudioBook::_ID_AUDIO_BOOK,
            AudioBook::_ID_PARENT,
            AudioBook::_ID_LANGUAGE,
            AudioBook::_ID_SERIES,
            AudioBook::_ID_GRADE,
            AudioBook::_TITLE,
            AudioBook::_DESCRIPTION,
            AudioBook::_EXTRA,
            AudioBook::_CONTENT,
            AudioBook::_THUMB,
            AudioBook::_AUDIO,
            AudioBook::_DURATION,
            AudioBook::_AUDIO_SIZE,
            AudioBook::_LEVEL_SYSTEM,
            AudioBook::_VERSION,
            AudioBook::_DATE_PUBLISH,
            AudioBook::_UPDATED_AT
        )
        ->distinct()
        ->where(AudioBook::_VERSION, '>=', $version)
        ->where(AudioBook::_VERSION, '>', 0)
        ->where(AudioBook::_ID_PARENT, 0)
        ->with('child');
        if ($idApp) {
            $query->where(AudioBook::_ID_APP, $idApp);
        }
        if ($idLanguage) {
            $query->where(AudioBook::_ID_LANGUAGE, $idLanguage);
        }
        if (isset($offset)) {
            $query->limit($limit)
                ->offset($offset);
        }
        return $audioBooks = $query->get();
    }

    public function getListIdAudioBookAndSeries($idApp, $idLanguage)
    {
        return $this->_model
            ->select(AudioBook::TABLE . '.' . AudioBook::_ID_AUDIO_BOOK, AudioBook::TABLE . '.' . AudioBook::_ID_SERIES,
                AudioBook::TABLE . '.' . AudioBook::_LEVEL_SYSTEM)
            ->whereIn(AudioBook::TABLE . '.' . AudioBook::_LEVEL_SYSTEM,
                [LevelSystem::LEVEL_SYSTEM_51, LevelSystem::LEVEL_SYSTEM_61, LevelSystem::LEVEL_SYSTEM_71])
            ->where(AudioBook::_ID_APP, $idApp)
            ->where(AudioBook::_ID_LANGUAGE, $idLanguage)
            ->get();
    }

    public function getContentAudioBookById($idAudioBook)
    {
        return $this->_model
            ->select(AudioBook::_CONTENT, AudioBook::_VERSION)
            ->where(AudioBook::_ID_AUDIO_BOOK, $idAudioBook)
            ->first();
    }

}
