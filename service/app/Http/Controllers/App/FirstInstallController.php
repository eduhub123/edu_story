<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\FirstInstallService;
use App\Services\Platform\VersionService;
use Illuminate\Http\Request;

class FirstInstallController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $request;
    private $versionService;
    private $firstInstallService;

    public function __construct(
        Request $request,
        VersionService $versionService,
        FirstInstallService $firstInstallService
    ) {
        $this->request             = $request;
        $this->versionService      = $versionService;
        $this->firstInstallService = $firstInstallService;
    }

    public function zipFirstInstallMS()
    {
        $idApp            = $this->request->input('id_app', 40);
        $subversion       = $this->request->input('subversion', '3.5.8');
        $deviceType       = $this->request->input('device_type', 'hd');
        $os               = $this->request->input('os');
        $isInHouse        = $this->request->input('in_house', false);
        $isLicence        = $this->request->input('is_licence', false);
        $detectUrlCluster = $this->request->input('detect_url_cluster', "https://www.monkeyuni.net");
        $dataVersion      = $this->request->input('data_version', []);
        $isMalay          = $this->request->input('is_malay', false);
        if (is_numeric($deviceType)) {
            if($deviceType == 2) {
                $deviceType = "hd";
            } else {
                $deviceType = "hdr";
            }
        }
        $dataVersion = $this->versionService->getDataVersionAppInfo($idApp, $dataVersion);

        $dataFirstInstall       = $this->firstInstallService->getDataFileFirstInstallMS($idApp, $deviceType, $os, $isLicence, $detectUrlCluster, $subversion, $isInHouse, $dataVersion, $isMalay);
        $linkFileFirstInstallMS = $this->firstInstallService->zipFileFirstInstallMS($idApp, $deviceType, $os, $subversion, $isInHouse, $dataVersion, $dataFirstInstall, $isMalay);

        $this->status  = 'success';
        $this->message = __('app.success');
        next:
        return $this->responseData($linkFileFirstInstallMS);
    }
}
