<?php

namespace App\Grpc\App;

use App\Services\Story2\WorksheetService;
use Mypackage\GetVersionWorksheetRequest;
use Mypackage\GetVersionWorksheetResponse;
use Mypackage\WorksheetServiceInterface;
use Spiral\GRPC;

class WorksheetController implements WorksheetServiceInterface
{

    public function GetVersionWorksheet(GRPC\ContextInterface $ctx, GetVersionWorksheetRequest $in): GetVersionWorksheetResponse
    {
        $idApp = $in->getIdApp();

        $worksheetService = app()->make(WorksheetService::class);

        $lastVersion = $worksheetService->getLastVersionWorksheet($idApp);

        $response = new GetVersionWorksheetResponse();
        $response->setData($lastVersion);
        return $response;
    }
}