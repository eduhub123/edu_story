<?php


namespace App\Services;

use Illuminate\Http\UploadedFile;

class UploadService
{

    const FILE_JSON                    = '/json';
    const FILE_GAME_IMAGE              = '/gameimages';
    const FILE_GAME_VIDEO              = '/gamevideo';
    const FILE_GAME_JSON               = '/gamejson';
    const FILE_GAME_CONFIG             = '/gameconfig';
    const FILE_THUMB_LESSSON           = '/thumb/lesson';
    const FILE_THUMB_COURSE            = '/thumb/course';
    const FILE_THUMB_GAME_CATEGORY_HDR = '/thumb/game-category/hdr';
    const FILE_THUMB_GAME_CATEGORY_HD  = '/thumb/game-category/hd';
    const FILE_THUMB_GAME_CATEGORY     = '/thumb/game-category';
    const FILE_AUDIO_GAME_CATEGORY     = '/audio/game-category';
    const FILE_MEDIA                   = '/media';
    const FILE_AUDIO                   = '/audio';
    const FILE_VIDEO                   = '/video';
    const FILE_IMAGES                  = '/images/hdr';
    const FILE_IMAGES_RESIZE           = '/images/hd';
    const FILE_GAFS                    = '/gafs';
    const FILE_ZIP                     = '/zip';
    const LOG_FILE_CONFIG_BOOK         = '/log-file-config-book';
    const FILE_UPLOAD                  = '/uploads';
    const FILE_COMMON_RESOURCE         = '/common-resource';
    const FILE_TYPE_MESSAGE            = '/type-message-json';
    const FILE_WORD_HDR                = '/hdr/word';
    const FILE_WORD_HD                 = '/hd/word';
    const FILE_WORD_IMAGES             = '/images';
    const FILE_CATEGORY_HDR            = '/hdr/category';
    const FILE_CATEGORY_HD             = '/hd/category';
    const FILE_LESSON                  = '/lesson';
    const FILE_FLOW                    = '/flow';

    const FILE_HDR = 'hdr';
    const FILE_HD  = 'hd';

    public static function zipFileLesson($fileName, $data)
    {
        try {
            $pathFolder = UploadService::FILE_ZIP .'/lesson_'.date("d_m_Y");
            $checkZip   = ZipService::zipFile($pathFolder, 'index.json', $fileName, json_encode($data, true));
            if (!$checkZip) {
                return false;
            }
            return $checkZip;
        } catch (\Exception $e) {
            echo $e->getMessage().PHP_EOL;
            return false;
        }
    }

    public static function zipFileLesson2($fileName, $data)
    {
        try {
            $pathFolder = UploadService::FILE_ZIP .'/lesson_check_lesson';
            $checkZip   = ZipService::zipFile($pathFolder, 'index.json', $fileName, json_encode($data, true));
            if (!$checkZip) {
                return false;
            }
            return $checkZip;
        } catch (\Exception $e) {
            echo $e->getMessage().PHP_EOL;
            return false;
        }
    }

    public static function zipFirstInstall($pathFolder, $fileName, $data)
    {
        try {
            $checkZip = ZipService::zipFile($pathFolder, 'index.json', $fileName, json_encode($data, true));
            if (!$checkZip) {
                return false;
            }
            return $checkZip;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return false;
        }
    }


    public static function getFile($realFile)
    {
        $path_parts             = pathinfo($realFile);
        $path_parts['basename'] = $path_parts['filename'] . '.' . $path_parts['extension'];
        $finfo                  = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type              = finfo_file($finfo, $realFile);

        return new UploadedFile(
            $realFile,
            $path_parts['basename'],
            $mime_type,
            filesize($realFile),
            true
        );
    }
}
