<?php

namespace App\Services\Story2;

use App\Models\Globals\ListApp;
use App\Models\Story2\AudioBook;
use App\Models\Story2\LevelSystem;
use App\Models\Story2\Series;
use App\Models\Story2\Translate;
use App\Repositories\Story2\AudioBookRepository;
use App\Services\RedisService;
use Illuminate\Support\Facades\Config;

class AudioBookService
{
    private $audioBookRepos;
    private $seriesService;
    private $redisService;

    const KEY_REDIS_AUDIO_BOOK_SERIES_V2    = 'KEY_REDIS_AUDIO_BOOK_SERIES_V2';
    const KEY_REDIS_AUDIO_BOOK_SERIES_V2_VM = 'KEY_REDIS_AUDIO_BOOK_SERIES_V2_VM';
    const KEY_REDIS_AUDIO_BOOKS_V2          = "KEY_REDIS_AUDIO_BOOKS_V2";

    public function __construct(
        AudioBookRepository $audioBookRepos,
        SeriesService $seriesService,
        RedisService $redisService
    ) {
        $this->audioBookRepos = $audioBookRepos;
        $this->seriesService  = $seriesService;
        $this->redisService   = $redisService;
    }

    public function getLastVersionAudioBook($idApp, $idLanguage)
    {
        return $this->audioBookRepos->getLastVersionAudioBook($idApp, $idLanguage) ?? 0;
    }

    public function getContentAudioBookById($idAudioBook){
        return $this->audioBookRepos->getContentAudioBookById($idAudioBook);
    }

    //AudioBooks
    public function getAudioBooks($idApp, $idLanguage, $version = 0, $lastVersion = 0)
    {
        $keyAudioBook  = self::KEY_REDIS_AUDIO_BOOKS_V2 . "_" . $idApp . "_" . $idLanguage . "_" . $version . "_" . $lastVersion;
        $listAudioBook = $this->redisService->get($keyAudioBook, true);
        if (!$listAudioBook) {
            $listAudioBook = $this->audioBookRepos->getAudioBooks($idApp, $idLanguage, $version)->toArray();
            $this->redisService->set($keyAudioBook, $listAudioBook);
        }
        return $listAudioBook;
    }

    //processDataAudioBook
    public function processDataAudioBook($idApp,  $idLanguage, $version = 0, $lastVersion = 0 , $isInHouse = false)
    {
        $listAudioBook = $this->getAudioBooks($idApp, $idLanguage, $version, $lastVersion);
        $list          = [];
        $delete        = [];
        foreach ($listAudioBook as $audioBook) {
            $idAudioBook = $audioBook[AudioBook::_ID_AUDIO_BOOK];

            $status = LevelSystem::checkStatusLevelSystem($audioBook[AudioBook::_LEVEL_SYSTEM], $audioBook[AudioBook::_DATE_PUBLISH], $isInHouse);
            if ($status == LevelSystem::STATUS_NEW) {
                continue;
            } elseif ($status == LevelSystem::STATUS_DELETE) {
                $delete[$idAudioBook] = intval($idAudioBook);
                $delete = $this->deleteChild($audioBook, $delete);
                continue;
            }
            $audiobookNew = $this->getItemAudioBookByApp($audioBook, $isInHouse, $idApp);
            if (count($audiobookNew['child']) > 0) {
                $audioBookChildNew = [];
                foreach ($audiobookNew['child'] as $indexChild => &$audioBookChild) {
                    $status = LevelSystem::checkStatusLevelSystem($audioBookChild[AudioBook::_LEVEL_SYSTEM], $audioBookChild[AudioBook::_DATE_PUBLISH], $isInHouse);
                    if ($status == LevelSystem::STATUS_NEW) {
                        continue;
                    } elseif ($status == LevelSystem::STATUS_DELETE) {
                        $delete[$audioBookChild[AudioBook::_ID_AUDIO_BOOK]] = intval($audioBookChild[AudioBook::_ID_AUDIO_BOOK]);
                        unset($audiobookNew['child'][$indexChild]);
                        continue;
                    }
                    $audioBookChildNew[] = $this->getItemAudioBookByApp($audioBookChild, $isInHouse, $idApp);
                }
                $audiobookNew['child'] = array_values($audioBookChildNew);
            }
            $list[] = $audiobookNew;
        }
        return [$list, $delete];
    }

    private function deleteChild($audioBook, $delete)
    {
        if (count($audioBook['child']) > 0) {
            foreach ($audioBook['child'] as $audioBookChild) {
                $delete[$audioBookChild[AudioBook::_ID_AUDIO_BOOK]] = intval($audioBookChild[AudioBook::_ID_AUDIO_BOOK]);
            }
        }
        return $delete;
    }

    private function getItemAudioBookByApp($audioBook, $isInHouse, $idApp)
    {
        if ($idApp == ListApp::APP_ID_MS_VN) {
            return $this->getItemAudioBookVM($audioBook, $isInHouse);
        }
        return $this->getItemAudioBook($audioBook, $isInHouse);
    }

    private function getItemAudioBook($audioBook, $isInHouse= false)
    {
        return [
            'id'                 => intval($audioBook[AudioBook::_ID_AUDIO_BOOK]),
            'title'              => preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $audioBook[AudioBook::_TITLE]),
            'lang_id'            => intval($audioBook[AudioBook::_ID_LANGUAGE]),
            'cateId'             => intval($audioBook[AudioBook::_ID_GRADE]),
            'description'        => $audioBook[AudioBook::_DESCRIPTION] ?? '',
            'content'            => $audioBook[AudioBook::_CONTENT] ?? '',
            'extra'              => $audioBook[AudioBook::_EXTRA],
            'thumb_image'        => AudioBook::PATH_UPLOAD_THUMB . "/" . $audioBook[AudioBook::_THUMB],
            'audio_file'         => AudioBook::PATH_UPLOAD_AUDIO . "/" . $audioBook[AudioBook::_AUDIO],
            'duration'           => intval($audioBook[AudioBook::_DURATION]),
            'audio_file_size'    => $audioBook[AudioBook::_AUDIO_SIZE] ? (float)$audioBook[AudioBook::_AUDIO_SIZE] : 0,
            'version_audio_book' => intval($audioBook[AudioBook::_VERSION]),
            'date_publish'       => $audioBook[AudioBook::_DATE_PUBLISH] != 0 ? $audioBook[AudioBook::_DATE_PUBLISH] : ( $isInHouse ? $audioBook[AudioBook::_UPDATED_AT] : 0 ),
            'child'              => $audioBook['child'] ?? [],
        ];
    }

    private function getItemAudioBookVM($audioBook, $isInHouse = false)
    {
        return [
            'id'                 => intval($audioBook[AudioBook::_ID_AUDIO_BOOK]),
            'title'              => preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $audioBook[AudioBook::_TITLE]),
            'lang_id'            => intval($audioBook[AudioBook::_ID_LANGUAGE]),
            'cateId'             => intval($audioBook[AudioBook::_ID_GRADE]),
            'duration'           => intval($audioBook[AudioBook::_DURATION]),
            'description'        => $audioBook[AudioBook::_DESCRIPTION] ?? '',
            'content'            => $audioBook[AudioBook::_CONTENT] ?? '',
            'thumb_image'        => $audioBook[AudioBook::_THUMB] ? Config::get('environment.URL_DISPLAY_CDN') . AudioBook::PATH_UPLOAD_THUMB . "/" . $audioBook[AudioBook::_THUMB] : "",
            'version_audio_book' => intval($audioBook[AudioBook::_VERSION]),
            'quality'            => 0,
            'score'              => 0,
            'date_publish'       => $audioBook[AudioBook::_DATE_PUBLISH] != 0 ? $audioBook[AudioBook::_DATE_PUBLISH] : ($isInHouse ? $audioBook[AudioBook::_UPDATED_AT] : 0),
            'extra'              => $audioBook[AudioBook::_EXTRA],
            'audio_file'         => $audioBook[AudioBook::_AUDIO] ? Config::get('environment.URL_DISPLAY_CDN') . AudioBook::PATH_UPLOAD_AUDIO . "/" . $audioBook[AudioBook::_AUDIO] : '',
            'audio_file_size'    => $audioBook[AudioBook::_AUDIO_SIZE] ? (float)$audioBook[AudioBook::_AUDIO_SIZE] : 0,
            'child'              => $audioBook['child'] ?? [],
        ];
    }

    //DataSeries
    public function getDataSeries($idApp, $idLanguage, $idLangDisplay, $lastVersion, $isInHouse = false)
    {
        $key        = self::KEY_REDIS_AUDIO_BOOK_SERIES_V2 . '_' . $idApp . '_' . $idLangDisplay . '_' . $lastVersion . '_' . $isInHouse;
        $dataSeries = $this->redisService->get($key, true);
        if (!$dataSeries) {
            $listSeries                  = $this->seriesService->getListSeriesByLangDisplay($idApp, $idLangDisplay, $lastVersion);
            $idAudioBooksGroupByIdSeries = $this->audioBookRepos->getListIdAudioBookAndSeries($idApp, $idLanguage)->groupBy(Series::_ID_SERIES)->toArray();

            $dataSeries = [];
            foreach ($listSeries as $idSeries => $item) {
                $idAudioBooks = [];
                if (isset($idAudioBooksGroupByIdSeries[$idSeries])) {
                    $idAudioBooksNotInHouse = [];
                    $idAudioBooksInHouse    = [];
                    foreach ($idAudioBooksGroupByIdSeries[$idSeries] as $audioBook) {
                        $idAudioBooksInHouse[] = $audioBook[AudioBook::_ID_AUDIO_BOOK];
                        if ($audioBook[AudioBook::_LEVEL_SYSTEM] == LevelSystem::LEVEL_SYSTEM_71) {
                            $idAudioBooksNotInHouse[] = $audioBook[AudioBook::_ID_AUDIO_BOOK];
                        }
                    }
                    if ($isInHouse) {
                        $idAudioBooks = $idAudioBooksInHouse;
                    } else {
                        $idAudioBooks = $idAudioBooksNotInHouse;
                    }
                }
                $thumb = Series::PATH_UPLOAD_THUMB . "/" . $item[Series::_THUMB];
                if ($idApp == ListApp::APP_ID_MS_VN) {
                    $thumb = Config::get('environment.URL_DISPLAY_CDN') . $thumb;
                }
                $dataSeries[$idSeries] = [
                    'id'     => $idSeries,
                    'title'  => $item[Translate::_VALUE],
                    'thumb'  => $thumb,
                    'book'   => $idAudioBooks,
                    'hidden' => Series::convertStatusToHidden($item[Series::_STATUS]),
                ];
            }
            $this->redisService->set($key, $dataSeries, 3600);
        }
        return array_values($dataSeries);
    }

}
