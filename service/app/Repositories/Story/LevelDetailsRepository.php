<?php

namespace App\Repositories\Story;

use App\Models\Story\LevelDetails;
use App\Models\Story\StoryCategory;
use App\Models\Story\StoryLang;
use App\Models\Story\StoryLevel;
use App\Repositories\EloquentRepository;

class LevelDetailsRepository extends EloquentRepository
{

    public function getModel()
    {
        return LevelDetails::class;
    }

    public function getAllLevel()
    {
        return $this->_model
            ->select(
                LevelDetails::TABLE . '.' . LevelDetails::_LEVEL,
                LevelDetails::TABLE . '.' . LevelDetails::_DESCRIPTION,
                LevelDetails::TABLE . '.' . LevelDetails::_TEXT,
                LevelDetails::TABLE . '.' . LevelDetails::_GRADE_ID
            )
            ->join(StoryLevel::TABLE, StoryLevel::TABLE . '.' . StoryLevel::_LEVEL_ID, LevelDetails::TABLE . '.' . LevelDetails::_ID)
            ->orderBy(LevelDetails::_LEVEL, 'ASC');
    }

    public function getAllLevelByLang($langId)
    {
        return $this->_model
            ->select(
                LevelDetails::TABLE . '.' . LevelDetails::_LEVEL,
                LevelDetails::TABLE . '.' . LevelDetails::_DESCRIPTION,
                LevelDetails::TABLE . '.' . LevelDetails::_TEXT,
                LevelDetails::TABLE . '.' . LevelDetails::_GRADE_ID,
                LevelDetails::TABLE . '.' . LevelDetails::_LANG_ID
            )
            ->distinct()
            ->whereIn(LevelDetails::TABLE . '.' . LevelDetails::_LANG_ID, $langId)
            ->where(LevelDetails::TABLE . '.' . LevelDetails::_GRADE_ID, '!=', '""')
            ->join(StoryLevel::TABLE, StoryLevel::TABLE . '.' . StoryLevel::_LEVEL_ID, LevelDetails::TABLE . '.' . LevelDetails::_ID)
            ->orderBy(LevelDetails::TABLE . '.' . LevelDetails::_LEVEL, 'ASC')
            ->get();
    }
}
