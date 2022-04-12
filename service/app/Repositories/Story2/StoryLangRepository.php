<?php

namespace App\Repositories\Story2;

use App\Models\Story2\StoryLang;
use App\Repositories\EloquentRepository;

class StoryLangRepository extends EloquentRepository
{
    public function getModel()
    {
        return StoryLang::class;
    }

    public function getLastVersionStory($idLangStory, $isNetworkEarlyStart)
    {
        $query = $this->_model
            ->select('api_ver')
            ->where('id_lang_story', $idLangStory);
        if (!$isNetworkEarlyStart) {
            $query = $query->where('go_to_live', StoryLang::GO_TO_LIVE);
        }

        return $query->orderBy('api_ver', 'desc')->first();
    }
}