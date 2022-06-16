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

    public function processDataAudioBook($idApp,  $idLanguage, $version, $lastVersion, $isInHouse)
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
                continue;
            }
            $audiobookNew = $this->getItemAudioBook($audioBook);
            if (count($audiobookNew['child']) > 0) {
                foreach ($audiobookNew['child'] as $indexChild => &$audioBookChild) {
                    $status = LevelSystem::checkStatusLevelSystem($audioBookChild[AudioBook::_LEVEL_SYSTEM], $audioBookChild[AudioBook::_DATE_PUBLISH], $isInHouse);
                    if ($status == LevelSystem::STATUS_NEW) {
                        continue;
                    } elseif ($status == LevelSystem::STATUS_DELETE) {
                        $delete[] = intval($audioBookChild[AudioBook::_ID_AUDIO_BOOK]);
                        unset($audiobookNew['child'][$indexChild]);
                        continue;
                    }
                    $audioBookChild = $this->getItemAudioBook($audioBookChild);
                }
                $audiobookNew['child'] = array_values($audiobookNew['child']);
            }
            $list[] = $audiobookNew;
        }
        return [$list, $delete];
    }

    private function getItemAudioBook($audioBook)
    {
        return [
            'id'                 => intval($audioBook[AudioBook::_ID_AUDIO_BOOK]),
            'title'              => preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $audioBook[AudioBook::_TITLE]),
            'lang_id'            => intval($audioBook[AudioBook::_ID_LANGUAGE]),
            'cateId'             => intval($audioBook[AudioBook::_ID_GRADE]),
            'description'        => $audioBook[AudioBook::_DESCRIPTION] ?? '',
            'extra'              => $audioBook[AudioBook::_EXTRA],
            'thumb_image'        => AudioBook::PATH_UPLOAD_THUMB . "/" . $audioBook[AudioBook::_THUMB],
            'audio_file'         => AudioBook::PATH_UPLOAD_AUDIO . "/" . $audioBook[AudioBook::_AUDIO],
            'duration'           => intval($audioBook[AudioBook::_DURATION]),
            'audio_file_size'    => $audioBook[AudioBook::_AUDIO_SIZE] ? (float)$audioBook[AudioBook::_AUDIO_SIZE] : 0,
            'version_audio_book' => intval($audioBook[AudioBook::_VERSION]),
            'date_publish'       => $audioBook[AudioBook::_DATE_PUBLISH],
            'child'              => $audioBook['child'] ?? [],
        ];
    }

    public function getDataSeries($idApp, $idLanguage, $idLangDisplay, $lastVersion)
    {
        $key        = self::KEY_REDIS_AUDIO_BOOK_SERIES_V2 . '_' . $idApp . '_' . $idLangDisplay . '_' . $lastVersion;
        $dataSeries = $this->redisService->get($key, true);
        if (!$dataSeries) {
            $listSeries                  = $this->seriesService->getListSeries($idApp, $idLangDisplay, $lastVersion);
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
}
