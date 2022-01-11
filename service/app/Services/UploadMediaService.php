<?php


namespace App\Services;


use App\Jobs\UploadFileMedia;
use App\Services\ServiceConnect\MediaConnectService;
use Aws\S3\MultipartUploader;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class UploadMediaService
{
    protected $mediaConnectService;

    const KEY_REDIS_FILE_MEDIA = "KEY_REDIS_FILE_MEDIA";

    public function __construct(
        MediaConnectService $mediaConnectService
    ) {
        $this->mediaConnectService = $mediaConnectService;
    }

    public static function uploadFileMediaQueue($pathFile, $addPathUpload, $type = 1)
    {
        $folderUpload = 'App' . $addPathUpload;
        Queue::push(new UploadFileMedia($pathFile, $folderUpload, $type));
    }

    public function uploadFileMedia($pathFile, $addPathUpload, $type)
    {
        $folderUpload = 'App' . $addPathUpload;
        if ($type == 1) {
            $response = $this->mediaConnectService->upload($pathFile, $folderUpload);
        } else {
            $response = UploadMediaService::uploadListFile($pathFile, $folderUpload);
//            $response = $this->mediaConnectService->uploadList($pathFile, $folderUpload);
        }
        if (isset($response['status']) && $response['status'] == 'success') {
            foreach ($response['data'] as $dataFile) {
                if (!$dataFile['isSuccess']) {
                    echo "Upload file media fail: " . $dataFile['message'] ?? "" . PHP_EOL;
                }
            }
        } else {
            CacheErrorService::setCacheError("ERROR_UPLOAD_MEDIA", ["list_file" => $pathFile, 'folder_upload' => $folderUpload]);
            echo "Upload file media fail " . $response['data'] ? json_encode($response['data']) : "" . PHP_EOL;
        }
    }

    public function readFile($fileName, $addPath, $addPathUpload = UploadService::FILE_UPLOAD)
    {
        $pathFile = 'App' . $addPathUpload . $addPath . "/" . $fileName;
        $response = $this->mediaConnectService->readFile($pathFile);
        if (isset($response['status']) && $response['status'] == 'success') {
            $data    = $response['data'];
            $message = $response ? $response['message'] : 'Read file media success';
            return [$data, $message];
        }
        $message = 'Read file media fail ' . ($response['message'] ?? "");

        return [false, $message];
    }

    public static function uploadListFile($listFile, $folder)
    {
        $listData = [];
        foreach ($listFile as $key => $filePath) {
            $fileName = UploadMediaService::getFileNameByPath($filePath);
            $mimeType = File::mimeType($filePath);
            list($check, $message) = UploadMediaService::uploadFile($filePath, $fileName, $mimeType, $folder, false);
            if ($check) {
                $isSuccess = true;
            } else {
                $isSuccess = false;
            }
            $listData[$key] = [
                'isSuccess' => $isSuccess,
                'data'      => $check,
                'message'   => $message
            ];
        }
        $response['status'] = "success";
        $response['data']   = $listData;
        return $response;
    }

    public static function uploadFile($realPath, $fileName, $mimeType, $folder, $autoFileName)
    {
        $redisService = new RedisService();
        $fileName     = UploadMediaService::convertNameFile($fileName);
        if ($autoFileName) {
            $fileName = time() . "_" . $fileName;
        }
        $path     = $folder . '/' . $fileName;
        $disk     = Storage::disk('s3');
        $uploader = UploadMediaService::makeUploader($disk, $realPath, $path, $mimeType);
        try {
            //S3
            $result = $uploader->upload();
            $linkS3 = $result['ObjectURL'];
            $redisService->hSet(self::KEY_REDIS_FILE_MEDIA, $fileName, $linkS3);
        } catch (\Exception $exception) {
            return [false, $exception->getMessage()];
        }
        return [$linkS3, null];
    }

    public static function makeUploader($disk, $realPath, $filePath, $mimeType = null)
    {
        return new MultipartUploader(
            $disk->getDriver()->getAdapter()->getClient(),
            $realPath,
            [
                'Bucket'          => env('AWS_BUCKET'),
                'Key'             => $filePath,
                'ACL'             => 'public-read',
                'before_initiate' => function (\Aws\Command $command) use ($mimeType) {
                    $command['ContentType'] = $mimeType;
                }
            ]
        );
    }

    public static function getFileNameByPath($path)
    {
        $path      = trim($path);
        $arrayPath = explode("/", $path);
        return $arrayPath[count($arrayPath) - 1];
    }

    public static function convertNameFile($name)
    {
        $name = preg_replace('/[\/\s]+/', '_', $name);
        $name = preg_replace('/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/', "a", $name);
        $name = preg_replace('/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/', "e", $name);
        $name = preg_replace('/ì|í|ị|ỉ|ĩ/', "i", $name);
        $name = preg_replace('/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/', "o", $name);
        $name = preg_replace('/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/', "u", $name);
        $name = preg_replace('/ỳ|ý|ỵ|ỷ|ỹ/', "y", $name);
        $name = preg_replace('/đ/', "d", $name);
        $name = preg_replace('/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/', "A", $name);
        $name = preg_replace('/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/', "E", $name);
        $name = preg_replace('/Ì|Í|Ị|Ỉ|Ĩ/', "I", $name);
        $name = preg_replace('/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/', "O", $name);
        $name = preg_replace('/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/', "U", $name);
        $name = preg_replace('/Ỳ|Ý|Ỵ|Ỷ|Ỹ/', "Y", $name);
        $name = preg_replace('/Đ/', "D", $name);
        $name = preg_replace("/'/", "", $name);
        $name = preg_replace('/"/', "", $name);
        return $name;
    }

}
