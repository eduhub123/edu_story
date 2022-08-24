<?php


namespace App\Services\Platform;


use App\Services\RedisService;
use App\Services\ServiceConnect\LessonConnectService;
use Illuminate\Support\Facades\Config;

class GameService
{
    private $lessonConnectService;
    private $redisService;

    const KEY_REDIS_LIST_GAME = "KEY_REDIS_LIST_GAME";

    public function __construct(
        LessonConnectService $lessonConnectService,
        RedisService $redisService
    ) {
        $this->lessonConnectService = $lessonConnectService;
        $this->redisService         = $redisService;
    }

    public function getDataListGame($version)
    {
        $keyRedis = self::KEY_REDIS_LIST_GAME . "_" . $version;
        $listGame = $this->redisService->get($keyRedis, true);
        if (!$listGame) {
            $listGame = $this->lessonConnectService->getListGame();
            if ($listGame) {
                $this->redisService->set($keyRedis, json_encode($listGame, true));
            }
        }
        return $listGame;
    }

    public function getDataListGameAppInfo($appId, $versionNumber, $versionGame)
    {
        if ($versionNumber == 2047) {
            $versionNumber = 0;
        }
        $listGame = $this->getDataListGame($versionGame);
        $data     = [];
        foreach ($listGame as $gameId => $game) {
            if ($game['version_number'] <= $versionNumber) {
                continue;
            }
            foreach ($game['list_app'] as $dataApp) {
                if ($dataApp['app_id'] == $appId) {
                    $data[$gameId] = [
                        "id"          => $game['id'],
                        "name"        => $game['name'],
                        "path_config" => $game['path_config'],
                        "path_images" => ($dataApp['thumb']) ? (Config::get('path.PATH_DOWNLOAD_CDN') . "/uploads/gameimages/" . $dataApp['thumb']) : "",
                        "zip_size"    => $game['zip_size'],
                    ];
                }
            }
        }
        return [
            'version_game_number' => $versionGame,
            'list'                => array_values($data)
        ];
    }

    public function getDataListGameMS($appId, $versionNumber, $versionGame)
    {
        $listGame     = $this->getDataListGame($versionGame);
        $data         = [];
        $folderConfig = env('APP_ENV') == 'live' ? '/live/game-data/config/' : '/uploads/gameconfig/';
        $folderImage  = env('APP_ENV') == 'live' ? '/live/game-data/images/' : '/uploads/gameimages/';
        foreach ($listGame as $gameId => $game) {
            if ($game['version_number'] <= $versionNumber) {
                continue;
            }
            foreach ($game['list_app'] as $dataApp) {
                if ($dataApp['app_id'] == $appId) {
                    $data[$gameId] = [
                        "id"          => $game['id'],
                        "name"        => $game['name'],
                        "path_config" => $game['path_config'] ? $folderConfig . $game['path_config'] : '',
                        "path_images" => $dataApp['thumb'] ? $folderImage . $dataApp['thumb'] : $folderImage . $game['path_images'],
                        "zip_size"    => $game['zip_size'],
                    ];
                }
            }
        }
        return [
            'version_game_number' => $versionGame,
            'list'                => array_values($data)
        ];
    }
}
