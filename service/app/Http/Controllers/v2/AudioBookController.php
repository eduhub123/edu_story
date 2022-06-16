<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\BaseMobileController;
use App\Models\Language;
use App\Models\LangDisplay;
use App\Models\Story2\PopularSearch;
use App\Services\Platform\VersionService;
use App\Services\Story2\AudioBookService;
use App\Services\Story2\PopularSearchService;
use App\Services\ZipService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AudioBookController extends BaseMobileController
{
    private $request;
    private $zipService;
    private $audioBookService;
    private $versionService;
    private $popularSearchService;

    public function __construct(
        Request $request,
        ZipService $zipService,
        AudioBookService $audioBookService,
        VersionService $versionService,
        PopularSearchService $popularSearchService
    ) {
        parent::__construct($request);
        $this->request              = $request;
        $this->zipService           = $zipService;
        $this->audioBookService     = $audioBookService;
        $this->versionService       = $versionService;
        $this->popularSearchService = $popularSearchService;
    }

    public function getAudioBook()
    {
        $data      = [];
        $json      = $this->request->input('json', false);
        $inHouse   = $this->request->input('in_house');
        $isInHouse = $this->isNetworkEarlyStart || $inHouse;

        $idLanguage    = Language::getIdLanguageByIdApp($this->app_id);
        $idLangDisplay = LangDisplay::getIdLangDisplayByIdApp($this->app_id);
        $lastVersion   = $this->versionService->getVersion($this->app_id, VersionService::TYPE_AUDIO_BOOK_V2);
        if ($lastVersion <= $this->ver) {
            $this->status = 'success';
            goto next;
        }

        if (!$json) {
            $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
            $fileZip = $this->zipService->getPathFileZip($this->app_id, 'audiobook_v2_' . $today, 'audiobook', $this->ver, $lastVersion);
            if (file_exists($fileZip)) {
                goto nextDownload;
            }
        }

        list($audioBooks, $delete) = $this->audioBookService->processDataAudioBook($this->app_id, $idLanguage, $this->ver, $lastVersion, $isInHouse);

        $data['list_audio_book'] = array_values($audioBooks);
        $data['delete']          = array_values($delete);
        $data['version_audio']   = $lastVersion;
        $data['popular_search']  = $this->popularSearchService->getPopularSearchV2($this->app_id, [PopularSearch::POPULAR_AUDIO]);
        $data['Series']          = $this->audioBookService->getDataSeries($this->app_id, $idLanguage, $idLangDisplay, $lastVersion);

        $this->message = __('app.success');
        $this->status  = 'success';

        next :
        if ($json) {
            return $this->responseData($data);
        }
        $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
        $fileZip = $this->zipService->zipDataForAPiDownload($this->app_id, 'audiobook_v2_' . $today, $data, 'audiobook', 0, $lastVersion, "", $this->status);

        nextDownload :
        return response()->download($fileZip);
    }

}
