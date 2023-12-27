<?php


namespace App\Services;

use App\Jobs\SendTelegram;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class ZipService
{

    public function __construct()
    {
    }

    public function zipDataForAPiDownload(
        $appId,
        $subFolder,
        $data,
        $apiName,
        $verApi = 0,
        $newVersion = 0,
        $deviceId = "",
        $status = "success",
        $isNetworkES = "",
        $isFile = false
    ) {
        $folderData = 'zip_app/';
        if (!file_exists($folderData)) {
            mkdir($folderData, 0777);
        }
        $folderData .= $subFolder;
        if (!file_exists($folderData)) {
            mkdir($folderData, 0777);
        }
        $isNetworkES  = $isNetworkES ? 1 : 0;
        $isSuccess    = $status == "success" ? 1 : 0;
        $fileName     = $apiName . "_" . $verApi . '_' . $newVersion . "_" . $appId . "_" . $deviceId . "_" . $isSuccess . "_" . $isNetworkES;
        $filePathJson = $folderData . $fileName . '.json';
        $dataWrite    = $data ? json_encode($data) : '{}';
        $writeFile    = File::put($filePathJson, $dataWrite);
        if ($writeFile && file_exists($filePathJson)) {
            $json = json_decode(file_get_contents($filePathJson), true);
            if ($data != $json) {
                $writeFile = File::put($filePathJson, $dataWrite);
                Queue::push(new SendTelegram("error file save not in json format " . $filePathJson));
                return false;
            }
        }
        if ($writeFile && file_exists($filePathJson)) {
            $pathFileZip = $folderData . '/' . $fileName . '.zip';
            if($isFile){
                $checkZip    = ExtendedZip::zipFile($filePathJson, $pathFileZip, \ZipArchive::CREATE, $fileName . '.json');
            } else {
                $checkZip    = ExtendedZip::zipFile($filePathJson, $pathFileZip);
            }
            unlink($filePathJson);
            if (!$checkZip) {
                return false;
            }
            return $pathFileZip;
        }
        return false;
    }

    public function getPathFileZip(
        $appId,
        $subFolder,
        $apiName,
        $verApi = 0,
        $newVersion = 0,
        $deviceId = "",
        $isNetworkES = ""
    ) {
        $folderData  = 'zip_app/';
        $folderData  .= $subFolder;
        $isNetworkES = $isNetworkES ? 1 : 0;
        $isSuccess   = 1;
        $fileName    = $apiName . "_" . $verApi . '_' . $newVersion . "_" . $appId . "_" . $deviceId . "_" . $isSuccess . "_" . $isNetworkES;
        return $folderData . '/' . $fileName . '.zip';
    }

    public static function zipFile($subFolder, $fileName, $fileZip, $data, $folderData = null)
    {
        try {
            if ($folderData == null) {
                $fullUrlStorage = Storage::disk('local')->getAdapter()->getPathPrefix();
                $folderData     = $fullUrlStorage;
            }
            if (!file_exists($folderData)) {
                mkdir($folderData, 0777, true);
            }
            $folderData .= $subFolder;
            if (!file_exists($folderData)) {
                mkdir($folderData, 0777, true);
            }

            $pathFile  = $folderData . '/' . $fileZip . "_" . $fileName;
            $dataWrite = $data;
            $writeFile = File::put($pathFile, $dataWrite);
            if ($writeFile && file_exists($pathFile)) {
                $json = file_get_contents($pathFile);
                if ($data != $json) {
                    $writeFile = File::put($pathFile, $dataWrite);
                    Queue::push(new SendTelegram("error file save with incorrect data " . $pathFile));
                    return false;
                }
            }
            if ($writeFile && file_exists($pathFile)) {
                $pathFileZip = $folderData . '/' . $fileZip . '.zip';
                ExtendedZip::zipFile($pathFile, $pathFileZip, \ZipArchive::CREATE, $fileName);
//                unlink($pathFile);
                return $pathFileZip;
            }
            echo "Not exits file zip $pathFile" . PHP_EOL;
            return false;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return false;
        }
    }

    public static function checkInvalidJson($pathFile)
    {
        $fullUrlStorage = Storage::disk('local')->getAdapter()->getPathPrefix();
        $folderData     = $fullUrlStorage;
        if (!file_exists($folderData)) {
            mkdir($folderData, 0777, true);
        }
        $folderDataExtract = $folderData . '/unzip';
        if (!file_exists($folderDataExtract)) {
            mkdir($folderDataExtract, 0777, true);
        }
        $zip = new \ZipArchive;
        if ($zip->open($pathFile) === true) {
            $zip->extractTo($folderDataExtract);
            $zip->close();
            $pathJson = $folderDataExtract . '/index.json';
            $dataJson = file_get_contents($pathJson);
            $dataJson = json_decode($dataJson, true);
            unlink($pathJson);
            if (!$dataJson) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

}
