<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\BaseMobileController;
use App\Models\Language;
use App\Models\LangDisplay;
use App\Models\Story2\AudioBook;
use App\Models\Story2\FreeStory;
use App\Models\Story2\PopularSearch;
use App\Services\Platform\VersionService;
use App\Services\Story2\AudioBookService;
use App\Services\Story2\FreeStoryService;
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
    private $freeStoryService;
    private $popularSearchService;

    public function __construct(
        Request $request,
        ZipService $zipService,
        AudioBookService $audioBookService,
        VersionService $versionService,
        FreeStoryService $freeStoryService,
        PopularSearchService $popularSearchService
    ) {
        parent::__construct($request);
        $this->request              = $request;
        $this->zipService           = $zipService;
        $this->audioBookService     = $audioBookService;
        $this->versionService       = $versionService;
        $this->freeStoryService     = $freeStoryService;
        $this->popularSearchService = $popularSearchService;
    }

    public function getAudioBook()
    {
        $data      = [];
        $json      = $this->request->input('json', false);
        $inHouse   = $this->request->input('in_house', false);
        $version   = (int)$this->request->input('version', 0);

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
            $fileZip = $this->zipService->getPathFileZip($this->app_id, 'audiobook_v2_' . $today, 'audiobook', $version, $lastVersion);
            if (file_exists($fileZip)) {
                goto nextDownload;
            }
        }

        list($audioBooks, $delete) = $this->audioBookService->processDataAudioBook($this->app_id, $idLanguage, $version, $lastVersion, $isInHouse);

        $data['list_audio_book'] = array_values($audioBooks);
        $data['delete']          = array_values($delete);
        $data['version_audio']   = $lastVersion;
        $data['popular_search']  = $this->popularSearchService->getPopularSearchV2($this->app_id, [PopularSearch::POPULAR_AUDIO]);
        $data['Series']          = $this->audioBookService->getDataSeries($this->app_id, $idLanguage, $idLangDisplay, $lastVersion, $isInHouse);

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

    public function listVM()
    {
        $data      = [];
        $json      = $this->request->input('json', false);
        $inHouse   = $this->request->input('in_house', false);
        $version   = (int)$this->request->input('ver', 0);

        $isInHouse = $this->isNetworkEarlyStart || $inHouse;

        $today         = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
        $idLanguage    = Language::getIdLanguageByIdApp($this->app_id);
        $idLangDisplay = LangDisplay::getIdLangDisplayByIdApp($this->app_id);
        $lastVersion   = $this->versionService->getVersion($this->app_id, VersionService::TYPE_AUDIO_BOOK_V2);
        if ($lastVersion <= $this->ver) {
            $this->status = 'success';
            goto next;
        }

        if (!$json) {
            $fileZip = $this->zipService->getPathFileZip($this->app_id, 'audiobook_v2_' . $today, 'audiobook_vm', $version, $lastVersion);
            if (file_exists($fileZip)) {
                goto nextDownload;
            }
        }

        //list_audio_book
        list($audioBooks, $delete) = $this->audioBookService->processDataAudioBook($this->app_id, $idLanguage, $version, $lastVersion, $isInHouse);

        $freeAudioBook = $this->freeStoryService->getFreeStories($this->app_id, FreeStory::TYPE_AUDIO, $today);

        $data['list_audio_book'] = [
            'delete'          => array_values($delete),
            'data'            => array_values($audioBooks),
            'free_audio_book' => $freeAudioBook,
            'today'           => date('Ymd', time()),
            'version'         => $lastVersion
        ];
        //series
        $series = $this->audioBookService->getDataSeries($this->app_id, $idLanguage, $idLangDisplay, $lastVersion, $isInHouse);
        $data['info']['Series']  = $series;
        //popular_search
        $data['popular_search']  =  $this->popularSearchService->getPopularSearchV2MV($this->app_id, [PopularSearch::POPULAR_AUDIO]);

        $this->message = __('app.success');
        $this->status  = 'success';

        next :
        if ($json) {
            return $this->responseData($data);
        }
        $fileZip = $this->zipService->zipDataForAPiDownload($this->app_id, 'audiobook_v2_' . $today, $data, 'audiobook_vm', $version, $lastVersion, "", $this->status, '', true);

        nextDownload :
        return response()->download($fileZip);
    }

    public function detail()
    {
        $id   = $this->request->input('id');
        $json = $this->request->input('json', false);

        $content = '';
        $version = 0;
        if (!$id) {
            goto next;
        }

        $contentAudioBook = $this->audioBookService->getContentAudioBookById($id);
        if ($contentAudioBook) {
            $content = $contentAudioBook[AudioBook::_CONTENT];
            $version = $contentAudioBook[AudioBook::_VERSION];
        }

        if (!$json) {
            $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
            $fileZip = $this->zipService->getPathFileZip($this->app_id, 'audiobook_detail_v2_' . $today . $id, 'audio_book_detail_v2', 0, $version);
            if (file_exists($fileZip)) {
                goto nextDownload;
            }
        }

        if($content){
            $this->message = __('app.success');
            $this->status  = 'success';
        }

        next:
        if ($json) {
            echo json_encode($content);die;
        }
        $today   = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;
        $fileZip = $this->zipService->zipDataForAPiDownload($this->app_id, 'audiobook_detail_v2_' . $today. $id, $content, 'audio_book_detail_v2', 0,$version, '', $this->status, '', true);
        nextDownload :
        return response()->download($fileZip)->deleteFileAfterSend(false);
    }

}
