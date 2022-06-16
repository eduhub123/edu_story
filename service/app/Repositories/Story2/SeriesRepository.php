<?php


namespace App\Repositories\Story2;

use App\Models\Story\StoryLang;
use App\Models\Story\StoryLevel;
use App\Models\Story2\Series;
use App\Models\Story2\Translate;
use App\Repositories\EloquentRepository;

class SeriesRepository extends EloquentRepository
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Series::class;
    }

    public function getSeries($idApp, $idLangDisplay)
    {
        return $this->_model
            ->select(Series::_ID_SERIES, Series::_THUMB, Series::_KEY_NAME, Series::_STATUS, Translate::_VALUE)
            ->leftJoin(Translate::TABLE, function ($q) use ($idLangDisplay) {
                $q->on(Translate::TABLE . '.' . Translate::_KEY, Series::TABLE . '.' . Series::_KEY_NAME)
                    ->where(Translate::TABLE . '.' . Translate::_ID_LANG_DISPLAY, $idLangDisplay);
            })
            ->where(Series::_STATUS, Series::STATUS_ACTIVE)
            ->where(Series::_ID_APP, $idApp)
            ->get();
    }


}
