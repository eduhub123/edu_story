<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\BaseMobileController;
use App\Models\Platform\VersionApiLoad;
use App\Models\Story2\PopularSearch;
use App\Models\Story\StoryLang;
use App\Repositories\Story\StoryLangRepository;
use App\Services\Platform\VersionService;
use App\Services\ServiceConnect\AppConnectService;
use App\Services\Story2\PopularSearchService;
use App\Services\Story\StoryService;
use App\Services\ZipService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoryController extends BaseMobileController
{
    private $storyLangRepository;
    private $request;
    private $zipService;
    private $storyService;
    private $popularSearchService;
    private $appConnectService;
    private $versionService;

    public function __construct(
        StoryLangRepository $storyLangRepository,
        Request $request,
        ZipService $zipService,
        StoryService $storyService,
        PopularSearchService $popularSearchService,
        AppConnectService $appConnectService,
        VersionService $versionService
    ) {
        parent::__construct($request);
        $this->storyLangRepository  = $storyLangRepository;
        $this->request              = $request;
        $this->zipService           = $zipService;
        $this->storyService         = $storyService;
        $this->popularSearchService = $popularSearchService;
        $this->appConnectService    = $appConnectService;
        $this->versionService       = $versionService;
    }

    public function list()
    {
        if ($this->ver == 44990 || $this->ver == 45680 || $this->ver == 45685) {
            $this->ver = 0;
        }

        $storyItem = [];
        $level     = $this->request->input('level', 0);
        $level     = intval($level);
        $json      = $this->request->input('json');
        $inHouse   = $this->request->input('in_house');

        $this->isNetworkEarlyStart = $this->isNetworkEarlyStart || $inHouse;

        $verInfo     = $this->storyLangRepository->getLastVersionStory($this->lang_id, $this->isNetworkEarlyStart);
        $lastVersion = 0;
        if ($verInfo) {
            $lastVersion = $verInfo[StoryLang::_API_VER];
        }

        if ($lastVersion <= $this->ver) {
            $this->status = 'success';
            goto next;
        }

        if (!$json) {
            $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
            $fileZip = $this->zipService->getPathFileZip($this->app_id, 'list_story_' . $today, 'list_story', $this->ver, $lastVersion);
            if (file_exists($fileZip)) {
                goto nextDownload;
            }
        }

        $storyItem                  = $this->storyService->processDataStory($this->app_id, $this->device_type, $this->lang_id, $level, $this->ver, $lastVersion, $this->isNetworkEarlyStart);
        $storyItem['version_story'] = $lastVersion;

        $storyItem['popular_search'] = $this->popularSearchService->getPopularSearch($this->app_id, [PopularSearch::POPULAR_STORY]);

        $this->message = __('app.success');
        $this->status  = 'success';
        next:
        if ($json) {
            return $this->ResponseData($storyItem);
        }
        $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
        $fileZip = $this->zipService->zipDataForAPiDownload($this->app_id, 'list_story_' . $today, $storyItem, 'list_story', 0, $lastVersion, "", $this->status);

        nextDownload :
        return response()->download($fileZip)->deleteFileAfterSend(false);
    }

    public function getTrialItems(Request $request)
    {
        $data = [];

        if ($this->validateBase($request, ['profile_id' => 'required'])) {
            goto next;
        }

        $profile = $this->appConnectService->getInfoProfile($request->profile_id);

        if (count($profile) <= 0) {
            $this->message = __('app.profile_not_found_by_account');
            goto next;
        }

        $data = $this->storyService->processTrialItems($profile);

        $data['last_version'] = $this->versionService->getVersion($this->app_id, VersionService::TYPE_TRIAL_ITEMS_MS);
        $data['age'] = $profile['age'];

        $this->status  = 'success';
        $this->message = __('app.success');

        next:
        return $this->responseData($data);
    }
}
