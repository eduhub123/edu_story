<?php

namespace App\Repositories\Story2;

use App\Models\Story2\StoryLang;
use App\Models\Story2\Worksheet;
use App\Models\Story2\WorksheetAssignLesson;
use App\Repositories\EloquentRepository;

class WorksheetRepository extends EloquentRepository
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Worksheet::class;
    }

    public function getLastVersionWorksheet($idApp)
    {
        return $maxVersion = $this->_model
            ->where(Worksheet::_ID_APP, $idApp)
            ->max(Worksheet::_VERSION_NUMBER);
    }

    public function getWorksheets($idApp, $version, $inHouse)
    {
        $query = $this->_model
            ->select(
                Worksheet::TABLE . '.' . Worksheet::_ID,
                Worksheet::TABLE . '.' . Worksheet::_TYPE,
                Worksheet::TABLE . '.' . Worksheet::_NAME_ORIGINAL . ' as name_worksheet',
                Worksheet::TABLE . '.' . Worksheet::_UPDATED_AT . ' as date_publish',
                WorksheetAssignLesson::TABLE . '.' . WorksheetAssignLesson::_LESSON_ID
            )
            ->join(WorksheetAssignLesson::TABLE, Worksheet::TABLE . '.' . Worksheet::_ID, WorksheetAssignLesson::TABLE . '.' . WorksheetAssignLesson::_ID_COURSEWARE)
            ->where(Worksheet::TABLE . '.' . Worksheet::_ID_APP, $idApp)
            ->where(Worksheet::TABLE . '.' . Worksheet::_IS_ASSIGN, Worksheet::IS_ASSIGN)
            ->whereNull(Worksheet::TABLE . '.' . Worksheet::_IS_DELETED)
            ->where(Worksheet::TABLE . '.' . Worksheet::_VERSION_NUMBER, '>', $version)
            ->whereIn(Worksheet::TABLE . '.' . Worksheet::_PLATFORM, [Worksheet::PLATFORM_APP, Worksheet::PLATFORM_ALL])
            ->whereIn(Worksheet::TABLE . '.' . Worksheet::_TYPE, [Worksheet::TYPE_PHONIC, Worksheet::TYPE_STORY]);

        if (!$inHouse) {
            $query = $query->where(Worksheet::TABLE . '.' . Worksheet::_IS_PUBLISH, Worksheet::IS_PUBLISH);
        }
        return $query->get();
    }



}
