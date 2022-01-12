<?php

namespace App\Repositories\Story;

use App\Models\Story\LevelDetails;
use App\Models\Story\StoryCategory;
use App\Models\Story\StoryLang;
use App\Models\Story\StoryLevel;
use App\Repositories\EloquentRepository;

class StoryLangRepository extends EloquentRepository
{

    public function getModel()
    {
        return StoryLang::class;
    }

    public function getLastVersionStory($langId, $isNetworkEarlyStart)
    {
        $query = $this->_model
            ->select('api_ver')
            ->where('lang_id', $langId);
        if (!$isNetworkEarlyStart) {
            $query = $query->where('go_to_live', StoryLang::GO_TO_LIVE);
        }

        return $query->orderBy('api_ver', 'desc')->first();
    }

    public function getListById($listSlangId, $langId)
    {
        $query = $this->_model
            ->select(
                StoryLang::TABLE . '.' . StoryLang::_SLANG_ID,
                StoryLang::TABLE . '.' . StoryLang::_NAME,
                StoryLang::TABLE . '.' . StoryLang::_ICON,
                StoryLang::TABLE . '.' . StoryLang::_DELETE,
                StoryLang::TABLE . '.' . StoryLang::_DATA,
                LevelDetails::TABLE . '.' . LevelDetails::_LEVEL,
                StoryCategory::TABLE . '.' . StoryCategory::_CATE_ID
            )
            ->leftJoin(StoryLevel::TABLE, StoryLevel::TABLE . '.' . StoryLevel::_SLANG_ID, StoryLang::TABLE . '.' . StoryLang::_SLANG_ID)
            ->leftJoin(StoryCategory::TABLE, StoryCategory::TABLE . '.' . StoryCategory::_SLANG_ID, StoryLang::TABLE . '.' . StoryLang::_SLANG_ID)
            ->leftJoin(LevelDetails::TABLE, LevelDetails::TABLE . '.' . LevelDetails::_ID, StoryLevel::TABLE . '.' . StoryLevel::_LEVEL_ID)
            ->whereIn(StoryLang::TABLE . '.' . StoryLang::_SLANG_ID, $listSlangId);
        if ($langId) {
            $query->where(StoryLang::TABLE . '.' . StoryLang::_LANG_ID, $langId);
        }
        return $query->get();
    }
}
