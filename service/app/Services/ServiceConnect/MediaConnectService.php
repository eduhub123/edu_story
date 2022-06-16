<?php


namespace App\Services\ServiceConnect;


use App\Services\CurlService;
use Illuminate\Support\Facades\Config;

class MediaConnectService
{
    private $curlService;

    public function __construct(
        CurlService $curlService
    ) {
        $this->curlService = $curlService;
    }

    public function getFileInfoByPath($path)
    {
        $url          = Config::get('environment.API_SERVICE_MEDIA') . "/api/get-file-info";
        $data["path"] = $path;
        $response     = $this->curlService->curlGetData($url, $data);
        return json_decode($response, true);
    }

    public function uploadList($listFile, $folderPath, $description = '')
    {
        $url                 = Config::get('environment.API_SERVICE_MEDIA') . "/api/upload-list";
        $data["folder_path"] = $folderPath;
        if ($description) {
            $data['description'] = $description;
        }
        $response = $this->curlService->curlPostUploadListFile($url, $data, null, $listFile);
        return json_decode($response, true);
    }

    public function upload($file, $folderPath, $description = '', $overwrite = false, $fileOverwritePath = '')
    {
        $url                 = Config::get('environment.API_SERVICE_MEDIA') . "/api/upload";
        $data["folder_path"] = $folderPath;
        if ($description) {
            $data['description'] = $description;
        }
        if ($overwrite) {
            $data['overwrite']           = $overwrite;
            $data['file_overwrite_path'] = $fileOverwritePath;
        }
        $response = $this->curlService->curlPostUploadFile($url, $data, null, $file);
        return json_decode($response, true);
    }

    public function readFile($path)
    {
        $url          = Config::get('environment.API_SERVICE_MEDIA') . "/api/read-file-from-aws";
        $data['path'] = $path;
        $dataResponse = $this->curlService->curlGetData($url, $data);
        return json_decode($dataResponse, true);
    }
}
