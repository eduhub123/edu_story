<?php

namespace App\Repositories\Story2;

use App\Models\Story2\PopularSearch;
use App\Repositories\EloquentRepository;

class PopularSearchRepository extends EloquentRepository
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PopularSearch::class;
    }

    public function getPopularSearch($idApp, $types)
    {
        return $this->_model
            ->select(
                PopularSearch::_ID,
                PopularSearch::_KEYWORD,
                PopularSearch::_THUMB,
                PopularSearch::_SORT,
                PopularSearch::_TYPE,
                PopularSearch::_TIME_UPDATE . ' as date_publish'
            )
            ->where(PopularSearch::_ID_APP, $idApp)
            ->whereIn(PopularSearch::_TYPE, $types)
            ->where(PopularSearch::_STATUS, PopularSearch::STATUS_ACTIVE)
            ->get();
    }

}
