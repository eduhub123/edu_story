<?php

namespace App\Repositories\Story;

use App\Models\Story\StoryLang;
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
}
