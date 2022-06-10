<?php

namespace App\Repositories\Story2;

use App\Models\Story2\FreeStory;
use App\Repositories\EloquentRepository;

class FreeStoryRepository extends EloquentRepository
{
    public function getModel()
    {
        return FreeStory::class;
    }

    public function getFreeStory($idApp, $type, $time)
    {
        $query = $this->_model
            ->select(FreeStory::_ID_STORY, FreeStory::_ID_APP, FreeStory::_TYPE);
        if ($idApp) {
            $query->where(FreeStory::_ID_APP, $idApp);
        }
        if ($type) {
            $query->where(FreeStory::_TYPE, $type);
        }
        if ($time) {
            $query->where(FreeStory::_TIME_FREE, $time);
        }
        return $query->where(FreeStory::_STATUS, FreeStory::STATUS_ACTIVE)
            ->distinct()
            ->get();

    }

}
