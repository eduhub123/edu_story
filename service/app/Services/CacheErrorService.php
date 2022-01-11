<?php


namespace App\Services;


use App\Jobs\SendTelegram;
use Illuminate\Support\Facades\Queue;

class CacheErrorService
{
    private $redisService;

    public function __construct(
        RedisService $redisService
    ) {
        $this->redisService = $redisService;
    }

    public static function setCacheError($errorKey, $dataError, $sendTele = true)
    {
        $index        = time();
        $redisService = new RedisService();
        $redisService->hSet($errorKey, $index, json_encode($dataError, true));
        if ($sendTele) {
            Queue::push(new SendTelegram(config('environment.TELEGRAM_CHAT_ID_CREATE_ORDER'), $errorKey, config('environment.TELEGRAM_BOT_TOKEN_CREATE_ORDER')));
        }
    }

    public function triesCacheError($errorKey, $numberTries = 3)
    {
        $listError = $this->redisService->hGetAll($errorKey);
        foreach ($listError as $index => $error) {
            $dataError = json_decode($error, true);
            if (isset($dataError['tries']) && $dataError['tries'] >= $numberTries) {
                continue;
            }
            if ($errorKey == "ERROR_ZIP_LESSON") {
                $checkZip = UploadService::zipFileLesson($dataError['file_name'], $dataError['data']);
                if ($checkZip) {
                    UploadMediaService::uploadFileMediaQueue([$checkZip], UploadService::FILE_ZIP . UploadService::FILE_LESSON, 2);
                    $this->deleteCacheError($errorKey, $index, $dataError);
                } else {
                    $this->setNumberTries($errorKey, $index, $dataError);
                }
            } elseif ($errorKey == "ERROR_UPLOAD_MEDIA") {
                UploadMediaService::uploadFileMediaQueue($dataError['list_file'], UploadService::FILE_ZIP . UploadService::FILE_LESSON, 2);
                $this->deleteCacheError($errorKey, $index, $dataError);
            }
        }
    }

    private function deleteCacheError($errorKey, $index, $dataError)
    {
        $this->redisService->hDel($errorKey, $index);
        $this->redisService->hSet($errorKey . "_SUCC", $index, json_encode($dataError, true));
    }

    private function setNumberTries($errorKey, $index, $dataError)
    {
        if (!isset($dataError['tries'])) {
            $dataError['tries'] = 0;
        }
        $dataError['tries'] += 1;
        $this->redisService->hSet($errorKey, $index, json_encode($dataError, true));
    }
}
