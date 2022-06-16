<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\Story2\WorksheetService;
use Illuminate\Http\Request;

class WorksheetController extends Controller
{

    private $worksheetService;
    private $request;

    public function __construct(
        WorksheetService $worksheetService,
        Request $request
    ) {
        $this->worksheetService = $worksheetService;
        $this->request        = $request;
    }

    public function getVersionWorksheet()
    {
        $idApp = $this->request->input('id_app');

        $lastVersion   = $this->worksheetService->getLastVersionWorksheet($idApp);

        $this->status  = 'success';
        $this->message = __('app.success');
        return $this->responseData($lastVersion);
    }
}
