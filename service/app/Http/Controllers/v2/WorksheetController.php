<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\BaseMobileController;
use App\Models\Language;
use App\Models\Platform\VersionApiLoad;
use App\Models\Story2\PopularSearch;
use App\Models\Story2\Worksheet;
use App\Services\Platform\VersionService;
use App\Services\Story2\PopularSearchService;
use App\Services\Story2\WorksheetService;
use App\Services\ZipService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorksheetController extends BaseMobileController
{
    private $request;
    private $zipService;
    private $versionService;
    private $popularSearchService;
    private $worksheetService;

    public function __construct(
        Request $request,
        ZipService $zipService,
        VersionService $versionService,
        PopularSearchService $popularSearchService,
        WorksheetService $worksheetService,
    ) {
        parent::__construct($request);
        $this->request              = $request;
        $this->zipService           = $zipService;
        $this->versionService       = $versionService;
        $this->popularSearchService = $popularSearchService;
        $this->worksheetService     = $worksheetService;
    }

    public function getListWorksheet()
    {
        $data    = [];
        $json    = $this->request->input('json', false);
        $version = (int)$this->request->input('version', 0);

        $inHouse                   = $this->request->input('in_house');
        $this->isNetworkEarlyStart = $this->isNetworkEarlyStart || $inHouse;

        $lastVersion = $this->versionService->getVersion($this->app_id, VersionApiLoad::TYPE_WORKSHEET);
        if (!$lastVersion) {
            $this->message = __('app.not_data_showing');
            goto next;
        }

        if (!$json) {
            $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
            $fileZip = $this->zipService->getPathFileZip($this->app_id, 'worksheet_v2_' . $today, 'worksheet', $version, $lastVersion);
            if (file_exists($fileZip)) {
                goto nextDownload;
            }
        }

        $idLanguage = Language::getIdLanguageByIdApp($this->app_id);
        $data       = $this->worksheetService->getDataWorksheet($this->app_id, $idLanguage, $version, $this->device_type, $this->isNetworkEarlyStart);

        $dataPopularSearch = $this->popularSearchService->getPopularSearch($this->app_id, [PopularSearch::POPULAR_WORKSHEET_PHONIC, PopularSearch::POPULAR_WORKSHEET_STORY]);

        $data['popular_search']     = $dataPopularSearch;
        $data['max_worksheet_send'] = Worksheet::CONFIG_SEND;
        $data['version_worksheet']  = $lastVersion;

        $this->message = __('app.success');
        $this->status  = 'success';

        next :
        if ($json) {
            return $this->responseData($data);
        }
        $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
        $fileZip = $this->zipService->zipDataForAPiDownload($this->app_id, 'worksheet_v2_' . $today, $data, 'worksheet', $version, $lastVersion, "", $this->status);

        nextDownload :
        return response()->download($fileZip);
    }


}
