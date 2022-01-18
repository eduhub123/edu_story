<?php

namespace App\Repositories\Story;

use App\Models\Story\Category;
use App\Repositories\EloquentRepository;

class CategoryRepository extends EloquentRepository
{

    public function getModel()
    {
        return Category::class;
    }

    public function getAllCategory($langId)
    {
        return $this->_model
            ->distinct()
            ->where(Category::_LANG_ID, $langId)
            ->where(Category::_CHECK_CATE, Category::IS_ACTIVE)
            ->orderBy(Category::_ORDER_CATE, 'ASC')
            ->get();
    }
}
