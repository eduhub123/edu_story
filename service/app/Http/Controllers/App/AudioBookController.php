<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Services\Story2\AudioBookService;
use Illuminate\Http\Request;

class AudioBookController extends Controller
{

    private $audioBookService;
    private $request;

    public function __construct(
        AudioBookService $audioBookService,
        Request $request
    ) {
        $this->audioBookService = $audioBookService;
        $this->request          = $request;
    }

    public function getVersionAudioBook()
    {
        $idApp = $this->request->input('id_app');

        $idLanguage    = Language::getIdLanguageByIdApp($idApp);
        $lastVersion   = $this->audioBookService->getLastVersionAudioBook($idApp, $idLanguage);

        $this->status  = 'success';
        $this->message = __('app.success');
        return $this->responseData($lastVersion);
    }
}
