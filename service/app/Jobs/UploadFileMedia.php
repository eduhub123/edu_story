<?php


namespace App\Jobs;


use App\Services\CacheErrorService;
use App\Services\ServiceConnect\MediaConnectService;
use App\Services\UploadMediaService;

class UploadFileMedia extends Job
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    private $path;
    private $folderUpload;
    private $type;
    private $mediaConnectService;

    /**
     * UploadMedia constructor.
     *
     * @param $path
     * @param $folderUpload
     */
    public function __construct($path, $folderUpload, $type = 1)
    {
        $this->path         = $path;
        $this->folderUpload = $folderUpload;
        $this->type         = $type;
    }

    /**
     * Execute the job insert log mongo
     *
     * @param MediaConnectService $mediaConnectService
     * @return void
     */
    public function handle(
        MediaConnectService $mediaConnectService
    ) {
        $this->mediaConnectService = $mediaConnectService;
        $this->uploadDataFileMedia(3);
    }

    public function uploadDataFileMedia($numberTries)
    {
        if ($this->type == 1) {
            $response = $this->mediaConnectService->upload($this->path, $this->folderUpload);
        } else {
            $response = UploadMediaService::uploadListFile($this->path, $this->folderUpload);
//            $response = $this->mediaConnectService->uploadList($this->path, $this->folderUpload);
        }
        if (isset($response['status']) && $response['status'] == 'success') {
            foreach ($response['data'] as $dataFile) {
                if (!$dataFile['isSuccess']) {
                    echo "Upload file media fail " . ($dataFile['message'] ?? "") . PHP_EOL;
                }
            }
        } else {
            if ($numberTries == 3) {
                echo "ZIP FILE :". json_encode($this->path).PHP_EOL;
                CacheErrorService::setCacheError("ERROR_UPLOAD_MEDIA", ["list_file" => $this->path, 'folder_upload' => $this->folderUpload]);
            }
            if ($numberTries > 0) {
                $this->uploadDataFileMedia($numberTries - 1);
            }
            echo "numberTries ". $numberTries . " Upload file media fail " . ($response['data'] ? json_encode($response['data']) : "") . PHP_EOL;
        }
    }

    public function fail($exception = null)
    {
        var_dump($exception->getMessage());
    }
}
