<?php

namespace App\Grpc\App;

use App\Models\Language;
use App\Services\Story2\AudioBookService;
use Mypackage\AudioBookServiceInterface;
use Mypackage\GetVersionAudioBookRequest;
use Mypackage\GetVersionAudioBookResponse;
use Spiral\GRPC;

class AudioBookController implements AudioBookServiceInterface
{

    public function GetVersionAudioBook(GRPC\ContextInterface $ctx, GetVersionAudioBookRequest $in): GetVersionAudioBookResponse
    {
        $idApp = $in->getIdApp();

        $idLanguage    = Language::getIdLanguageByIdApp($idApp);
        $audioBookService = app()->make(AudioBookService::class);

        $lastVersion   = $audioBookService->getLastVersionAudioBook($idApp, $idLanguage);

        $response = new GetVersionAudioBookResponse();
        $response->setData($lastVersion);
        return $response;
    }
}