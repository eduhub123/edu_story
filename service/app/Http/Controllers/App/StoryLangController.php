<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Story\Category;
use App\Models\Story\DescriptionGrade;
use App\Models\Story\DescriptionLevel;
use App\Models\Story\Grade;
use App\Models\Story\LevelDetails;
use App\Models\Story\StoryLang;
use App\Repositories\Story\CategoryRepository;
use App\Repositories\Story\DescriptionLevelRepository;
use App\Repositories\Story\GradeRepository;
use App\Repositories\Story\LevelDetailsRepository;
use App\Repositories\Story\StoryLangRepository;
use Illuminate\Http\Request;

class StoryLangController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $gradeRepos;
    private $storyLangRepos;
    private $categoryRepos;
    private $levelDetailsRepos;
    private $descriptionLevelRepos;
    private $request;

    public function __construct(
        GradeRepository $gradeRepos,
        CategoryRepository $categoryRepos,
        StoryLangRepository $storyLangRepos,
        LevelDetailsRepository $levelDetailsRepos,
        DescriptionLevelRepository $descriptionLevelRepos,
        Request $request
    ) {
        $this->gradeRepos            = $gradeRepos;
        $this->categoryRepos         = $categoryRepos;
        $this->storyLangRepos        = $storyLangRepos;
        $this->levelDetailsRepos     = $levelDetailsRepos;
        $this->descriptionLevelRepos = $descriptionLevelRepos;
        $this->request               = $request;
    }

    public function getListById()
    {
        $data        = [];
        $listStoryId = $this->request->input('story_id');
        $langId      = $this->request->input('lang_id', 1);

        if (!$listStoryId) {
            $this->message = __('app.invalid_params');
            goto next;
        }

        $data = $this->storyLangRepos->getListById($listStoryId, $langId)->groupBy(StoryLang::_SLANG_ID);

        $this->status  = 'success';
        $this->message = __('app.success');

        next:
        return $this->responseData($data);
    }

    public function getAllLevelStory()
    {
        $data = $this->levelDetailsRepos->getAllLevel()->pluck(LevelDetails::_LEVEL)->toArray();

        $data = array_unique($data);

        $this->status  = 'success';
        $this->message = __('app.success');

        next:
        return $this->responseData($data);
    }

    public function getCategoryStory()
    {
        $data   = [];
        $langId = $this->request->input('lang_id');

        if (!$langId) {
            $this->message = __('app.invalid_params');
            goto next;
        }

        $listCategory = $this->categoryRepos->getAllCategory($langId)->toArray();

        foreach ($listCategory as $key => $value) {
            $data[$key]['category_id'] = $value[Category::_ID];
            $data[$key]['content']     = $value[Category::_CONTENT];
            $data[$key]['image']       = $value[Category::_IMAGE] ? 'HomeImage/' . $value[Category::_IMAGE] : '';
            $data[$key]['lang_id']     = $value[Category::_LANG_ID];
        }

        $this->status  = 'success';
        $this->message = __('app.success');

        next:
        return $this->responseData($data);
    }

    public function getAllLanguageUse()
    {
        $data = [];

        $listLanguageUse = $this->storyLangRepos->getAllLanguageUse()->toArray();

        $listLangId = array_column($listLanguageUse, StoryLang::_LANG_ID);
        $listLangId = array_filter($listLangId);

        $levelDetail = $this->levelDetailsRepos->getAllLevelByLang($listLangId)->toArray();

        foreach ($levelDetail as $item) {
            $langId            = $item[LevelDetails::_LANG_ID];
            $level['id']       = (int)$item[LevelDetails::_LEVEL];
            $level['des']      = $item[LevelDetails::_DESCRIPTION];
            $level['name']     = $item[LevelDetails::_TEXT];
            $level['grade_id'] = (int)$item[LevelDetails::_GRADE_ID];
            $data[$langId][]   = $level;
        }

        $this->status  = 'success';
        $this->message = __('app.success');

        next:
        return $this->responseData($data);
    }

    public function getListGradeStory()
    {
        $listGrade = $this->gradeRepos->getDataByLanguageDisplay()->toArray();

        $data = [];
        foreach ($listGrade as $grade) {
            $langDisplay          = (int)$grade[DescriptionGrade::_LANG_DISPLAY];
            $item['id']           = (int)$grade[Grade::_ID];
            $item['order']        = (int)$grade[Grade::_GROUP];
            $item['name']         = $grade[Grade::_NAME];
            $item['des']          = $grade[DescriptionGrade::_DES];
            $data[$langDisplay][] = $item;
        }

        $this->status  = 'success';
        $this->message = __('app.success');

        next:
        return $this->responseData($data);
    }

    public function getListDescriptionStory()
    {
        $data = [];

        $listDescription = $this->descriptionLevelRepos->getDescription()->toArray();

        foreach ($listDescription as $description) {
            $value['order']           = (int)$description[DescriptionLevel::_LEVEL_ORDER];
            $value['lang_display_id'] = (int)$description[DescriptionLevel::_LANG_DISPLAY];
            $value['description']     = $description[DescriptionLevel::_DESCRIPTION];
            $data[]                   = $value;
        }

        $this->status  = 'success';
        $this->message = __('app.success');

        next:
        return $this->responseData($data);
    }

}
