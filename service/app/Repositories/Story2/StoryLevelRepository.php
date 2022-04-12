<?php

namespace App\Repositories\Story2;

use App\Models\Story2\StoryLang;
use App\Models\Story2\StoryLevel;
use App\Repositories\EloquentRepository;

class StoryLevelRepository extends EloquentRepository
{
    public function getModel()
    {
        return StoryLevel::class;
    }

    public function getAllLevel($lang_id, $level, $ver, $limit = null, $offset = null, $isNetworkEarlyStart = false)
    {
        $data = $this->_model
            ->select('t3.id_story_lang', 't3.data', 't3.delete', 't3.icon', 't3.id_stories', 't3.id_languages', 't3.quality_score',
                't3.zip_size', 't3.version_story', 't3.date_publish')
            ->distinct()
            ->where('t3.api_ver', '>', $ver)
            ->where('t3.status', 'Publish')
            ->where('t3.ver', 1);

        if (!$isNetworkEarlyStart) {
            $data = $data->where('t3.go_to_live', StoryLang::GO_TO_LIVE);
        }

        $data->join('levels as t2', 'story_level.id_levels', '=', 't2.id_levels')
            ->join('story_lang as t3', 't3.id_story_lang', '=', 'story_level.id_story_lang');

        if ($level) {
            $data->where('t2.key_name', $level);
        }
        if ($lang_id) {
            $data->where('t3.id_languages', $lang_id);
        }

        if (isset($offset)) {
            $data->limit($limit)->offset($offset);
        }

        return $data->get();
    }
}