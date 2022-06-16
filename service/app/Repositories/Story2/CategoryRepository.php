<?php

namespace App\Repositories\Story2;

use App\Models\Story2\Category;
use App\Models\LangDisplay;
use App\Models\Story2\Translate;
use App\Repositories\EloquentRepository;

class CategoryRepository extends EloquentRepository
{

    public function getModel()
    {
        return Category::class;
    }

    public function getCategoriesByIdApp($idApp)
    {
        $idLangDisplay = LangDisplay::getIdLangDisplayByIdApp($idApp);
        return $this->_model
            ->select(
                Category::TABLE . "." . Category::_ID_CATEGORY,
                Category::TABLE . "." . Category::_ID_APP,
                Category::TABLE . "." . Category::_KEY_NAME,
                Category::TABLE . "." . Category::_IMAGE,
                Translate::TABLE . "." . Translate::_VALUE
            )
            ->distinct()
            ->leftJoin(Translate::TABLE, function ($q) use ($idLangDisplay) {
                $q->on(Translate::TABLE . '.' . Translate::_KEY, Category::TABLE . '.' . Category::_KEY_NAME)
                    ->where(Translate::TABLE . '.' . Translate::_ID_LANG_DISPLAY, $idLangDisplay);
            })
            ->where(Category::TABLE . "." . Category::_ID_APP, $idApp)
            ->where(Category::TABLE . "." . Category::_CHECK_CATE, Category::STATUS_ACTIVE)
            ->orderBy(Category::TABLE . "." . Category::_ORDER_CATE, 'ASC')
            ->get();
    }
}
