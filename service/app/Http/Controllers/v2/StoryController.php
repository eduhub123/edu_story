<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\BaseMobileController;
use App\Models\Language;
use App\Models\Story2\FreeStory;
use App\Models\Story2\PopularSearch;
use App\Services\Platform\VersionService;
use App\Services\Story2\CategoryService;
use App\Services\Story2\FreeStoryService;
use App\Services\Story2\GradeService;
use App\Services\Story2\LangDisplayService;
use App\Services\Story2\LevelService;
use App\Services\Story2\StoryService;
use App\Services\Story2\PopularSearchService;
use App\Services\ZipService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoryController extends BaseMobileController
{
    private $request;
    private $zipService;
    private $storyService;
    private $levelService;
    private $gradeService;
    private $categoryService;
    private $versionService;
    private $freeStoryService;
    private $popularSearchService;
    private $langDisplayService;

    public function __construct(
        Request $request,
        ZipService $zipService,
        StoryService $storyService,
        LevelService $levelService,
        GradeService $gradeService,
        CategoryService $categoryService,
        VersionService $versionService,
        FreeStoryService $freeStoryService,
        PopularSearchService $popularSearchService,
        LangDisplayService $langDisplayService
    ) {
        parent::__construct($request);
        $this->request              = $request;
        $this->zipService           = $zipService;
        $this->storyService         = $storyService;
        $this->levelService         = $levelService;
        $this->gradeService         = $gradeService;
        $this->categoryService      = $categoryService;
        $this->versionService       = $versionService;
        $this->freeStoryService     = $freeStoryService;
        $this->popularSearchService = $popularSearchService;
        $this->langDisplayService   = $langDisplayService;
    }

    public function list()
    {
        $this->ver = $this->storyService->convertVersion($this->ver);

        $level     = $this->request->input('level', 0);
        $level     = intval($level);
        $json      = $this->request->input('json');
        $inHouse   = $this->request->input('in_house', false);
        $isInHouse = $this->isNetworkEarlyStart || $inHouse;

        $idLanguage  = Language::getIdLanguageByIdApp($this->app_id);
        $lastVersion = $this->versionService->getVersion($this->app_id, VersionService::TYPE_STORY_V2);
        $storyItem   = [];
        if ($lastVersion <= $this->ver) {
            $this->status = 'success';
            goto next;
        }

        if (!$json) {
            $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
            $fileZip = $this->zipService->getPathFileZip($this->app_id, 'list_story_v2_' . $today, 'list_story', $this->ver, $lastVersion);
            if (file_exists($fileZip)) {
                goto nextDownload;
            }
        }

        list($story, $delete) = $this->storyService->processDataStory($this->app_id, $this->device_type, $idLanguage, $level, $this->ver, $lastVersion, $isInHouse);

        $storyItem['story']          = array_values($story);
        $storyItem['delete']         = array_values($delete);
        $storyItem['version_story']  = $lastVersion;
        $storyItem['popular_search'] = $this->popularSearchService->getPopularSearchV2($this->app_id, [PopularSearch::POPULAR_STORY]);

        $this->message = __('app.success');
        $this->status  = 'success';

        next:
        if ($json) {
            return $this->ResponseData($storyItem);
        }
        $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
        $fileZip = $this->zipService->zipDataForAPiDownload($this->app_id, 'list_story_v2_' . $today, $storyItem, 'list_story', 0, $lastVersion, "", $this->status);

        nextDownload :
        return response()->download($fileZip)->deleteFileAfterSend(false);
    }

    public function listVM()
    {
        $this->ver = $this->storyService->convertVersion($this->ver);

        $level     = $this->request->input('level', 0);
        $level     = intval($level);
        $json      = $this->request->input('json');
        $inHouse   = $this->request->input('in_house', false);
        $isInHouse = $this->isNetworkEarlyStart || $inHouse;

        $idLanguage  = Language::getIdLanguageByIdApp($this->app_id);
        $lastVersion = $this->versionService->getVersion($this->app_id, VersionService::TYPE_STORY_V2);
        $storyItem   = [];
        if ($lastVersion <= $this->ver) {
            $this->status = 'success';
            goto next;
        }

        if (!$json) {
            $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
            $fileZip = $this->zipService->getPathFileZip($this->app_id, 'list_story_v2_' . $today, 'list_story', $this->ver, $lastVersion);
            if (file_exists($fileZip)) {
                goto nextDownload;
            }
        }

        list($story, $delete) = $this->storyService->processDataStory($this->app_id, $this->device_type, $idLanguage, $level, $this->ver, $lastVersion, $isInHouse);

        list($levelIds, $dataLevels) = $this->levelService->getListLevel($this->app_id);
        $freeStories = $this->freeStoryService->getFreeStories($this->app_id, FreeStory::TYPE_STORY, time());

        $storyItem['level'] = [
            'delete'     => array_values($delete),
            'data'       => array_values($story),
            'free_story' => $freeStories,
            'all_level'  => array_values($levelIds),
            'ver'        => $lastVersion,
            'today'      => date('Ymd', time()),
        ];

        $storyItem['home'] = [
            'feature'               => [],
            'character'             => false,
            'category'              => $this->categoryService->getListCategoryVM($this->app_id),
            'level'                 => $dataLevels,
            'grade'                 => $this->gradeService->getListGrade($this->app_id),
            'list_language_display' => $this->langDisplayService->processDataLangDisplays(),
            'description'           => $this->levelService->processDataDescriptionLevel()
        ];

        $storyItem['popular_search'] = $this->popularSearchService->getPopularSearchV2MV($this->app_id, [PopularSearch::POPULAR_STORY]);

        $this->message = __('app.success');
        $this->status  = 'success';

        next:
        if ($json) {
            return $this->ResponseData($storyItem);
        }
        $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
        $fileZip = $this->zipService->zipDataForAPiDownload($this->app_id, 'list_story_v2_' . $today, $storyItem, 'list_story', 0, $lastVersion, "", $this->status);

        nextDownload :
        return response()->download($fileZip)->deleteFileAfterSend(false);
    }


}
