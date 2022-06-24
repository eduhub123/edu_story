<?php

namespace App\Services\Story2;

use App\Models\Language;
use App\Models\Story2\Category;
use App\Models\Story2\Translate;
use App\Repositories\Story2\CategoryRepository;

class CategoryService
{

    private $categoryRepos;

    public function __construct(
        CategoryRepository $categoryRepos
    ) {
        $this->categoryRepos = $categoryRepos;
    }

    public function getListCategory($idApp)
    {
        $categories = $this->categoryRepos->getCategoriesByIdApp($idApp)->toArray();
        $data       = [];
        foreach ($categories as $key => $category) {
            $data[$key]['category_id'] = $category[Category::_ID_CATEGORY];
            $data[$key]['content']     = $category[Translate::_VALUE];
            $data[$key]['image']       = $category[Category::_IMAGE] ? Category::PATH_UPLOAD_IMAGE_CATEGORY . "/" .$category[Category::_IMAGE] : '';
            $data[$key]['lang_id']     = Language::getIdLanguageByIdApp($category[Category::_ID_APP]);
        }
        return $data;
    }
}
