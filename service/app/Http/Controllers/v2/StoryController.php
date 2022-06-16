<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\BaseMobileController;
use App\Models\Language;
use App\Models\Story2\PopularSearch;
use App\Services\Platform\VersionService;
use App\Services\Story2\StoryService;
use App\Services\Story2\PopularSearchService;
use App\Services\ZipService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoryController extends BaseMobileController
{
    private $request;
    private $storyService;
    private $popularSearchService;
    private $versionService;
    private $zipService;

    public function __construct(
        Request $request,
        StoryService $storyService,
        PopularSearchService $popularSearchService,
        VersionService $versionService,
        ZipService $zipService
    ) {
        parent::__construct($request);
        $this->request              = $request;
        $this->storyService         = $storyService;
        $this->popularSearchService = $popularSearchService;
        $this->versionService       = $versionService;
        $this->zipService           = $zipService;
    }

    public function list()
    {
        $this->ver = $this->storyService->convertVersion($this->ver);

        $level     = $this->request->input('level', 0);
        $level     = intval($level);
        $json      = $this->request->input('json');
        $inHouse   = $this->request->input('in_house');
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

}
