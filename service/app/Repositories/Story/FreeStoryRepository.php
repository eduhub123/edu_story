<?php

namespace App\Repositories\Story;

use App\Models\Story\FreeStory;
use App\Repositories\EloquentRepository;

class FreeStoryRepository  extends EloquentRepository
{
    public function getModel()
    {
        return FreeStory::class;

    }

    public function getFreeStoryToDay(){
        $now = time();
        $year = intval(date('Y',$now));
        $month = intval(date('m',$now));
        $day = intval(date('d',$now));
        $condition = 'FIND_IN_SET('.$day.',days)';
        $where = array(
            ['year',$year],
            ['month',$month],
        );
        $data = $this->_model
            ->select('slang_id')
            ->whereraw($condition)
            ->where($where)
            ->whereHas('story_lang_relate', function($query) {
//                $query->where('go_to_live',1);
            })
            ->with('story_lang_relate')
            ->distinct()
            ->get();
        return $data;
    }

}
