<?php

namespace App\Services\Story2;

use App\Models\Story2\AudioBook;
use App\Models\Story2\LevelSystem;
use App\Models\Story2\Series;
use App\Models\Story2\Translate;
use App\Repositories\Story2\AudioBookRepository;
use App\Services\RedisService;

class AudioBookService
{
    private $audioBookRepos;
    private $seriesService;
    private $redisService;

    const KEY_REDIS_AUDIO_BOOK_SERIES_V2 = 'KEY_REDIS_AUDIO_BOOK_SERIES_V2';
    const KEY_REDIS_AUDIO_BOOKS_V2       = "KEY_REDIS_AUDIO_BOOKS_V2";

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
        return $this->audioBookRepos->getLastVersionAudioBook($idApp, $idLanguage);
    }

    public function processDataAudioBook($idApp,  $idLanguage, $version = 0, $lastVersion = 0 , $isInHouse = false)
    {
        $keyAudioBook  = self::KEY_REDIS_AUDIO_BOOKS_V2 . "_" . $idApp . "_" . $idLanguage . "_" . $version . "_" . $lastVersion;
        $listAudioBook = $this->redisService->get($keyAudioBook, true);
        if (!$listAudioBook) {
            $listAudioBook = $this->audioBookRepos->getAudioBooks($idApp, $idLanguage, $version)->toArray();
            $this->redisService->set($keyAudioBook, $listAudioBook);
        }
        $list   = [];
        $delete = [];
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
            $audiobookNew = $this->getItemAudioBook($audioBook, $isInHouse);
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
                    $audioBookChildNew[] = $this->getItemAudioBook($audioBookChild, $isInHouse);
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

    public function getDataSeries($idApp, $idLanguage, $idLangDisplay, $lastVersion)
    {
        $key        = self::KEY_REDIS_AUDIO_BOOK_SERIES_V2 . '_' . $idApp . '_' . $idLangDisplay . '_' . $lastVersion;
        $dataSeries = $this->redisService->get($key, true);
        if (!$dataSeries) {
            $listSeries                  = $this->seriesService->getListSeriesByLangDisplay($idApp, $idLangDisplay, $lastVersion);
            $idAudioBooksGroupByIdSeries = $this->audioBookRepos->getListIdAudioBookAndSeries($idApp, $idLanguage)->groupBy(Series::_ID_SERIES)->toArray();

            $dataSeries = [];
            foreach ($listSeries as $idSeries => $item) {
                $idAudioBooks = [];
                if (isset($idAudioBooksGroupByIdSeries[$idSeries])) {
                    $idAudioBooks = array_column($idAudioBooksGroupByIdSeries[$idSeries], AudioBook::_ID_AUDIO_BOOK);
                }
                $dataSeries[$idSeries]['id']     = $idSeries;
                $dataSeries[$idSeries]['title']  = $item[Translate::_VALUE];
                $dataSeries[$idSeries]['thumb']  = Series::PATH_UPLOAD_THUMB . "/" . $item[Series::_THUMB];
                $dataSeries[$idSeries]['book']   = $idAudioBooks;
                $dataSeries[$idSeries]['hidden'] = Series::convertStatusToHidden($item[Series::_STATUS]);
            }
            $this->redisService->set($key, $dataSeries, 3600);
        }
        return array_values($dataSeries);
    }

    public function getDataSeriesVM($idApp, $idLanguage, $idLangDisplay, $lastVersion)
    {

        $idLangDisplays = [1, 4];
//        $key        = self::KEY_REDIS_AUDIO_BOOK_SERIES_V2 . '_' . $idApp . '_' . $idLangDisplay . '_' . $lastVersion;
//        $dataSeries = $this->redisService->get($key, true);
//        if (!$dataSeries) {
            $listSeries                  = $this->seriesService->getListSeries($idApp, $lastVersion);
            dd($listSeries);
            $idAudioBooksGroupByIdSeries = $this->audioBookRepos->getListIdAudioBookAndSeries($idApp, $idLanguage)->groupBy(Series::_ID_SERIES)->toArray();

            $dataSeries = [];
            foreach ($listSeries as $idSeries => $item) {
                $idAudioBooks = [];
                if (isset($idAudioBooksGroupByIdSeries[$idSeries])) {
                    $idAudioBooks = array_column($idAudioBooksGroupByIdSeries[$idSeries], AudioBook::_ID_AUDIO_BOOK);
                }
                foreach ($idLangDisplays as $idLangDisplay){
                    $dataSeries[$idSeries][$idLanguage]  = $item[Translate::_VALUE];
                }
                $dataSeries[$idSeries]['id']     = $idSeries;
                $dataSeries[$idSeries]['book']   = $idAudioBooks;
                $dataSeries[$idSeries]['hidden'] = Series::convertStatusToHidden($item[Series::_STATUS]);
            }
//            $this->redisService->set($key, $dataSeries, 3600);
//        }
        return array_values($dataSeries);

//        if ($this->subversion <= '3.1.2' && $this->app_id != ListApp::APP_ID_MS_VN || $this->subversion < '1.6.6') {
//            return $this->_getSeriesOld($result);
//        }
//
//        $langId = $this->app_id == ListApp::APP_ID_MS_VN ? 4 : 1;
//        $listId = array_keys($result['data']);
//        $audioBookSeries = $this->audioBookSeriesRepository->getAudioBookSeriesBySid($listId, $langId)->keyBy('id')->toArray();
//        $arr = [];
//        foreach ($result['data'] as $key => $item) {
//            if (!isset($audioBookSeries[$key])) {
//                continue;
//            }
//
//            $res = $audioBookSeries[$key];
//            $arr[$key]['title'] = $res['name'];
//            $arr[$key]['thumb'] = config('environment.URL_DISPLAY_CDN').$res['thumb'];
//
//            $arr[$key]['id'] = $key;
//            if (!($result['hidden'][$key])) {
//                $arr[$key]['book'] = $item;
//            }
//            $arr[$key]['hidden'] = $result['hidden'][$key];
//        }
//        return array_values($arr);
    }

}
