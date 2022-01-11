<?php

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

    public function getPopularSearch($appId, $type)
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
            ->where(PopularSearch::_APP_ID, $appId)
            ->whereIn(PopularSearch::_TYPE, $type)
            ->where(PopularSearch::_STATUS, PopularSearch::IS_ACTIVE)
            ->get();
    }

}
