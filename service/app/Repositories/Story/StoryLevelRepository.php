<?php

namespace App\Repositories\Story;

use App\Models\Story\StoryLang;
use App\Models\Story\StoryLevel;
use App\Repositories\EloquentRepository;

class StoryLevelRepository extends EloquentRepository
{
    public function getModel()
    {
        return StoryLevel::class;
    }

    public function listlevelBySlangId($sId)
    {
        $data = $this->_model
            ->select('t2.level')
            ->where('story_level.slang_id', $sId)
            ->join('level_details as t2', 'story_level.level_id', '=', 't2.id')
            ->first();
        return $data->level ?? "";
    }

    public function getAllLevel($lang_id, $level, $ver, $limit = null, $offset = null, $isNetworkEarlyStart = false)
    {
        $data = $this->_model
            ->select('t3.slang_id', 't3.data', 't3.delete', 't3.icon', 't3.sid', 't3.lang_id', 't3.quality_score',
                't3.zip_size', 't3.version_story', 't3.date_publish')
            ->distinct()
            ->where('t3.api_ver', '>', $ver)
            ->where('t3.status', 'Publish')
            ->where('t3.ver', 1);

        if (!$isNetworkEarlyStart && env('APP_ENV') == 'live') {
            $data = $data->where('t3.go_to_live', StoryLang::GO_TO_LIVE);
        }

        $data->join('level_details as t2', 'story_level.level_id', '=', 't2.id')
            ->join('story_lang as t3', 't3.slang_id', '=', 'story_level.slang_id');

        if ($level) {
            $data->where('t2.level', $level);
        }
        if ($lang_id) {
            $data->where('t3.lang_id', $lang_id);
        }

        if (isset($offset)) {
            $data->limit($limit)->offset($offset);
        }

        return $data->get();
    }
}
