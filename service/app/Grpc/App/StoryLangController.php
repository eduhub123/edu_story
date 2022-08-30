<?php

namespace App\Grpc\App;

use App\Models\Story\Category;
use App\Models\Story\DescriptionGrade;
use App\Models\Story\DescriptionLevel;
use App\Models\Story\FreeStory;
use App\Models\Story\LevelDetails;
use App\Models\Story\StoryLang;
use App\Repositories\Story\CategoryRepository;
use App\Repositories\Story\DescriptionLevelRepository;
use App\Repositories\Story\FreeStoryRepository;
use App\Repositories\Story\GradeRepository;
use App\Repositories\Story\LevelDetailsRepository;
use App\Repositories\Story\StoryLangRepository;
use Illuminate\Support\Facades\Log;
use Mypackage\CategoryStory;
use Mypackage\DescriptionStory;
use Mypackage\GetAllLanguageUseRequest;
use Mypackage\GetAllLanguageUseResponse;
use Mypackage\GetAllLevelStoryRequest;
use Mypackage\GetAllLevelStoryResponse;
use Mypackage\GetCategoryStoryRequest;
use Mypackage\GetCategoryStoryResponse;
use Mypackage\GetListDescriptionStoryRequest;
use Mypackage\GetListDescriptionStoryResponse;
use Mypackage\GetListGradeRequest;
use Mypackage\GetListGradeResponse;
use Mypackage\GetListStoryByIdRequest;
use Mypackage\GetListStoryByIdResponse;
use Mypackage\GetListStoryByListIdRequest;
use Mypackage\GetListStoryByListIdResponse;
use Mypackage\GetStoryFreeRequest;
use Mypackage\GetStoryFreeResponse;
use Mypackage\Grade;
use Mypackage\LanguageUse;
use Mypackage\Level;
use Mypackage\Story;
use Mypackage\StoryByListId;
use Mypackage\StoryFree;
use Mypackage\StoryLangServiceInterface;
use Spiral\GRPC;

class StoryLangController implements StoryLangServiceInterface
{

    public function GetAllLevelStory(GRPC\ContextInterface $ctx, GetAllLevelStoryRequest $in): GetAllLevelStoryResponse
    {
        $repository = app()->make(LevelDetailsRepository::class);

        $data = $repository->getAllLevel()->pluck(LevelDetails::_LEVEL)->toArray();

        $response = new GetAllLevelStoryResponse();
        $response->setData(array_unique($data));

        return $response;
    }

    public function GetCategoryStory(GRPC\ContextInterface $ctx, GetCategoryStoryRequest $in): GetCategoryStoryResponse
    {
        try {
            $langId = $in->getLangId();
            if (!$langId) {
                throw new GRPC\Exception\NotFoundException(trans('app.invalid_params'));
            }

            $categoryRepository = app()->make(CategoryRepository::class);
            $listCategory = $categoryRepository->getAllCategory($langId)->toArray();

            $data = [];

            foreach ($listCategory as $value) {
                $item = new CategoryStory();
                $item->setCategoryId($value[Category::_ID]);
                $item->setContent($value[Category::_CONTENT]);
                $item->setImage($value[Category::_IMAGE] ? 'HomeImage/' . $value[Category::_IMAGE] : '');
                $item->setLangId($value[Category::_LANG_ID]);
                $data[] = $item;
            }

            $response = new GetCategoryStoryResponse();
            $response->setData($data);
            return $response;
        } catch (\Exception $exception) {
            throw new GRPC\Exception\GRPCException($exception->getMessage(), 13);
        }
    }

    public function GetAllLanguageUse(GRPC\ContextInterface $ctx, GetAllLanguageUseRequest $in): GetAllLanguageUseResponse
    {
        $storyLangRepository = app()->make(StoryLangRepository::class);
        $levelDetailRepository = app()->make(LevelDetailsRepository::class);

        $listLanguageUse = $storyLangRepository->getAllLanguageUse()->toArray();
        $listLangId = array_column($listLanguageUse, StoryLang::_LANG_ID);
        $listLangId = array_filter($listLangId);

        $levelDetail = $levelDetailRepository->getAllLevelByLang($listLangId)->toArray();

        $map = [];

        foreach ($levelDetail as $item) {
            $langId = $item[LevelDetails::_LANG_ID];
            $level = new Level();
            $level->setId((int)$item[LevelDetails::_LEVEL]);
            $level->setDes($item[LevelDetails::_DESCRIPTION]);
            $level->setName($item[LevelDetails::_TEXT]);
            $level->setGradeId((int)$item[LevelDetails::_GRADE_ID]);

            if (!isset($map[$langId])) {
                $map[$langId] = [];
            }
            $map[$langId][] = $level;
        }

        $data = [];
        foreach ($map as $key => $item) {
            $language = new LanguageUse();
            $language->setId(intval($key));
            $language->setLevels($item);
            $data[] = $language;
        }

        $response = new GetAllLanguageUseResponse();
        $response->setData($data);
        return $response;
    }

    public function GetListGrade(GRPC\ContextInterface $ctx, GetListGradeRequest $in): GetListGradeResponse
    {
        $gradeRepository = app()->make(GradeRepository::class);
        $listGrade = $gradeRepository->getDataByLanguageDisplay()->toArray();

        $data = [];
        foreach ($listGrade as $item) {
            $grade = new Grade();
            $grade->setId((int)$item[\App\Models\Story\Grade::_ID]);
            $grade->setName($item[\App\Models\Story\Grade::_NAME]);
            $grade->setOrder((int)$item[\App\Models\Story\Grade::_GROUP]);
            $grade->setDes($item[DescriptionGrade::_DES]);
            $data[] = $grade;
        }

        $response = new GetListGradeResponse();
        $response->setData($data);
        return $response;
    }

    public function GetListDescriptionStory(GRPC\ContextInterface $ctx, GetListDescriptionStoryRequest $in): GetListDescriptionStoryResponse
    {
        $descriptionLevelRepository = app()->make(DescriptionLevelRepository::class);
        $listDescription = $descriptionLevelRepository->getDescription()->toArray();

        $data = [];
        foreach ($listDescription as $description) {
            $descriptionStory = new DescriptionStory();
            $descriptionStory->setOrder((int)$description[DescriptionLevel::_LEVEL_ORDER]);
            $descriptionStory->setLangDisplayId((int)$description[DescriptionLevel::_LANG_DISPLAY]);
            $descriptionStory->setDescription($description[DescriptionLevel::_DESCRIPTION]);
            $data[] = $descriptionStory;
        }

        $response = new GetListDescriptionStoryResponse();
        $response->setData($data);
        return $response;
    }

    public function GetListStoryById(GRPC\ContextInterface $ctx, GetListStoryByIdRequest $in): GetListStoryByIdResponse
    {
        try {
            $listStoryId = get_grpc_repeated($in->getStoryId());
            $langId = $in->getLangId();

            if (!$listStoryId) {
                throw new GRPC\Exception\GRPCException(__('app.invalid_params'), 3);
            }

            $storyLangRepository = app()->make(StoryLangRepository::class);
            $data = $storyLangRepository->getListById($listStoryId, $langId);

            $result = [];

            foreach ($data as $item) {
                $story = new Story();
                $story->setSlangId($item['slang_id']);
                $story->setName($item['name']);
                $story->setIcon($item['icon']);
                $story->setDelete($item['delete']);
                $story->setData($item['data']);
                $story->setLevel($item['level']);
                $story->setCateId($item['cateId']);

                $result[] = $story;
            }


            $response = new GetListStoryByIdResponse();
            $response->setData($result);
            return $response;
        } catch (\Exception $exception) {
            throw new GRPC\Exception\GRPCException($exception->getMessage(), 13);
        }
    }

    public function GetListStoryByListId(GRPC\ContextInterface $ctx, GetListStoryByListIdRequest $in): GetListStoryByListIdResponse
    {
        try {
            $slangId = get_grpc_repeated($in->getSlangId());
            $storyLangRepository = app()->make(StoryLangRepository::class);

            $data = $storyLangRepository->getListByListId($slangId);

            $result = [];

            foreach ($data as $item) {
                $story = new StoryByListId();
                $story->setSlangId($item['slang_id']);
                $story->setSid($item['sid']);
                $story->setLangId($item['lang_id']);
                $story->setName($item['name']);
                $story->setZipSize($item['zip_size']);
                $story->setVersionStory($item['version_story']);

                $result[] = $story;
            }


            $response = new GetListStoryByListIdResponse();
            $response->setData($result);
            return $response;
        } catch (\Exception $exception) {
            throw new GRPC\Exception\GRPCException($exception->getMessage(), 13);
        }
    }

    public function GetStoryFree(GRPC\ContextInterface $ctx, GetStoryFreeRequest $in): GetStoryFreeResponse
    {
        $listFreeStory = app()->make(FreeStoryRepository::class)->getFreeStoryToDay()->toArray();

        $data = [];
        foreach ($listFreeStory as $freeStory) {
            if (isset($freeStory['story_lang_relate'][StoryLang::_LANG_ID])) {
                $langId = $freeStory['story_lang_relate'][StoryLang::_LANG_ID];
                $data[$langId][] = (int)$freeStory[FreeStory::_SLANG_ID];
            }
        }

        $result = [];

        foreach ($data as $key => $item) {
            $free = new StoryFree();
            $free->setLangId($key);
            $free->setList($item);

            $result[] = $free;
        }

        $response = new GetStoryFreeResponse();
        $response->setData($result);
        return $response;


    }
}