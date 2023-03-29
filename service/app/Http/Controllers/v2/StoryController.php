<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\BaseMobileController;
use App\Models\Language;
use App\Models\Story2\FreeStory;
use App\Models\Story2\PopularSearch;
use App\Models\Story2\StoryLang;
use App\Repositories\Story2\StoryLangRepository;
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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
    private $storyLangRepository;

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
        LangDisplayService $langDisplayService,
        StoryLangRepository $storyLangRepository
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
        $this->storyLangRepository  = $storyLangRepository;
    }

    public function list()
    {
        $this->ver = $this->storyService->convertVersion($this->ver);

        $level     = $this->request->input('level', 0);
        $level     = intval($level);
        $json      = $this->request->input('json');
        $inHouse   = $this->request->input('in_house', false);
        $isInHouse = $this->isNetworkEarlyStart || $inHouse;
        $isMalay   = $this->isMalayNetWork || $this->request->input('is_malay', false);

        $idLanguage  = Language::getIdLanguageByIdApp($this->app_id);
        $lastVersion = $this->versionService->getVersion($this->app_id, VersionService::TYPE_STORY_V2);
        $storyItem   = [];
        if ($lastVersion <= $this->ver) {
            $this->status = 'success';
            goto next;
        }

        if (!$json) {
            $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
            $fileZip = $this->zipService->getPathFileZip($this->app_id, 'list_story_v2_' . $today, 'list_story', $this->ver, $lastVersion, "", $isInHouse);
            if (file_exists($fileZip)) {
                goto nextDownload;
            }
        }

        list($story, $delete) = $this->storyService->processDataStory($this->app_id, $this->device_type, $idLanguage, $level, $this->ver, $lastVersion, $isInHouse, $isMalay);

        $storyItem['is_malay']       = $isMalay;
        $storyItem['is_network_malay']       = $this->isMalayNetWork;
        $storyItem['ip_malay']       = $this->ip;
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
        $fileZip = $this->zipService->zipDataForAPiDownload($this->app_id, 'list_story_v2_' . $today, $storyItem, 'list_story', 0, $lastVersion, "", $this->status, $isInHouse);

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

        $today       = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
        $idLanguage  = Language::getIdLanguageByIdApp($this->app_id);
        $lastVersion = $this->versionService->getVersion($this->app_id, VersionService::TYPE_STORY_V2);
        $storyItem   = [];
        if ($lastVersion <= $this->ver) {
            $this->status = 'success';
            goto next;
        }

        if (!$json) {
            $fileZip = $this->zipService->getPathFileZip($this->app_id, 'list_story_v2_' . $today, 'list_story_vm', $this->ver, $lastVersion, "", $isInHouse);
            if (file_exists($fileZip)) {
                goto nextDownload;
            }
        }

        list($story, $delete) = $this->storyService->processDataStory($this->app_id, $this->device_type, $idLanguage, $level, $this->ver, $lastVersion, $isInHouse);

        list($levelIds, $dataLevels) = $this->levelService->getListLevel($this->app_id);
        $freeStories = $this->freeStoryService->getFreeStories($this->app_id, FreeStory::TYPE_STORY, $today);

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
        $fileZip = $this->zipService->zipDataForAPiDownload($this->app_id, 'list_story_v2_' . $today, $storyItem, 'list_story_vm', $this->ver, $lastVersion, "", $this->status, $isInHouse, true);

        nextDownload :
        return response()->download($fileZip)->deleteFileAfterSend(false);
    }


    /**
     * @throws ValidationException
     */
    public function getStory(Request $request)
    {
        $this->validate($request, [
            'app_id' => 'required',
            'sid'    => 'required'
        ]);

        $data = [
            'story' => '',
            'status'=> false,
        ];

        $story = $this->storyLangRepository->getStoryLangByIdStory($request->all());

        if (!$story) {
            goto next;
        }

        $pathZip        = $request->is_hdr ? StoryLang::PATH_UPLOAD_ZIP_HDR : StoryLang::PATH_UPLOAD_ZIP_HD;
        $downloadLink   = $pathZip . "/" . $story[StoryLang::_PATH_ZIP_FILE];

        $data['story']  = Config::get('environment.URL_DISPLAY_CDN') . $downloadLink;
        $data['status'] = true;

        next:
        return response()->json($data);
    }


}
