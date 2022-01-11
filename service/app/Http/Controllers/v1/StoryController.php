<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\BaseMobileController;
use App\Services\Platform\VersionService;
use App\Services\RedisService;
use App\Services\ZipService;
use Illuminate\Http\Request;

class StoryController extends BaseMobileController
{
    private $request;
    private $versionService;
    private $redisService;
    private $zipService;

    public function __construct(
        FreeStoryRepository $freeStoryRepository,
        StoryLevelRepository $storyLevelRepository,
        Request $request,
        VersionService $versionService,
        RedisService $redisService,
        ZipService $zipService
    ) {
        parent::__construct($request);
        $this->request        = $request;
        $this->versionService = $versionService;
        $this->redisService   = $redisService;
        $this->zipService     = $zipService;
    }

    public function list()
    {
        dd($this->ver);
        if ($this->ver == 44990 || $this->ver == 45680 || $this->ver == 45685) {
            $this->ver = 0;
        }
        $storyItem   = [];
        $level       = $this->request->input('level', 0);
        $level       = intval($level);
        $keyLevelAll = 'story_level_all_level' . $this->lang_id . $this->ver . $this->os . $this->is_vn . "_";
//        $ver         = $this->_versionStoryRepository->getCurrentVersion();

        $verInfo = $this->_storyLangRepository->getLastVersionStory($this->lang_id, $this->isNetworkEarlyStart);
        $ver     = $verInfo->api_ver;

        $json  = $request->input('json');
        $today = date('Ymd', time());

        $keyDisplayLang = 'story_home_display_lang' . $this->os . $this->ver . $this->lang_id;

        if ($ver <= $this->ver) {
            $this->status = 'success';
            goto next;
        }

        $this->isSubmit = $this->commonService->isSubmitMobileApp($this->app_id, $this->os, $this->subversion);

        $storyItem['level']               = $this->processDataStory($level);
        $storyItem['level']['free_story'] = $this->processFreeStory();

        $allLevel = $this->redisService->get($keyLevelAll, true);
        if (!$allLevel) {
            $allLevel = $this->_levelDetailRepository->getAllLevel($this->lang_id);
            $this->redisService->set($keyLevelAll, $allLevel);
        }

        $storyItem['level']['all_level'] = $allLevel;
        $storyItem['home']['feature']    = $this->processDataFeature();
        $storyItem['home']['character']  = $this->processDataCharacter();
        $storyItem['home']['category']   = $this->processDataCategory();
        $storyItem['home']['level']      = $this->processDataLevel();
        $storyItem['home']['grade']      = $this->processDataGrade();

        $storyItem['home']['list_language_display'] = $this->redisService->get($keyDisplayLang, true);
        if (!$storyItem['home']['list_language_display']) {
            $storyItem['home']['list_language_display'] = $this->_languageDisplayRepository->getListLang();
            $this->redisService->set($keyDisplayLang, $storyItem['home']['list_language_display']);
        }

        $storyItem['home']['description'] = $this->processDataDescriptionLevel();

        $storyItem['level']['ver']   = $ver;
        $storyItem['level']['today'] = date('Ymd', time());

        next:

        $storyItem['popular_search'] = $this->popularSearchService->getPopularSearch([PopularSearch::POPULAR_STORY], $this->app_id, $this->os, $this->lang_id);

        if ($json) {
            return $this->ResponseData($storyItem);
        }

        $fileZip = $this->zipService->zipDataForAPiDownload($this->app_id, 'story_list' . $today, $storyItem,
            'list_story_v2' . $this->isSubmit,
            $this->ver, $ver, $this->isNetworkEarlyStart);

        return response()->download($fileZip)->deleteFileAfterSend(false);
    }

    private function processDataStory($level)
    {
        $keyLevelStory = 'story_level_data_'.$this->os.$this->lang_id.$this->ver.$this->sv.$this->is_vn.'_'.$this->isSubmit.'_'.$this->isNetworkEarlyStart;
        $dataTmp       = $this->redisService->get($keyLevelStory, true);
        $list          = [];

        if (!$dataTmp) {

            $storyLevel = $this->_storyLevelRepository->getAllLevel($this->lang_id, $level, $this->ver, null, null,$this->isNetworkEarlyStart);

            $getAllLesson = $this->_gameLessonRepository->getAllLessonIdWithStoryId();

            $storyLesson = $this->activitiesStoriesRepository->getStoryIdWithActId($getAllLesson);

            foreach ($storyLevel as $k => $e) {

                if ($e->delete == StoryLevel::IS_DELETE) {
                    $dataTmp['delete'][] = intval($e->slang_id);
                    continue;
                }
                if ($e->data) {
                    if ($e->zip_size) {
                        $zip_size = json_decode($e->zip_size, true);
                    }

                    $list[$k]                           = json_decode($e->data, true);
                    $list[$k]['has_act']                = isset($storyLesson[$e->slang_id]) ? 1 : 0;
                    $list[$k]['image']                  = $this->_server->createUrlDownload('images/thumbnail/hd/'.$e->icon,
                        $this->sv, '', $this->is_vn);

                    if ($this->app_id == 51) {
                        $list[$k]['quality']            = (string) $e->quality_score;
                    } else {
                        $list[$k]['quality']            = $e->quality_score;
                    }

                    //set file name by version update
                    if ($e['date_publish'] > 1615546200 && $this->app_id == 40) {
                        $fileNameHd = $e->sid . '_' . $e->lang_id . '_' . $e->version_story . '_hd.zip';
                        $fileNameHdr = $e->sid . '_' . $e->lang_id . '_' . $e->version_story . '_hdr.zip';
                    } else {
                        $fileNameHd = $e->sid . '_' . $e->lang_id . '_hd.zip';
                        $fileNameHdr = $e->sid . '_' . $e->lang_id. '_hdr.zip';
                    }

                    $list[$k]['download_link_hd'] = $this->_server->createUrlDownload($fileNameHd,
                        0, 'zip', $this->is_vn);
                    $list[$k]['download_link_hdr'] = $this->_server->createUrlDownload($fileNameHdr,
                        0, 'zip', $this->is_vn);

                    $list[$k]['download_link_hd_size']  = isset($zip_size['hd']) ? (float)$zip_size['hd'] : 0;
                    $list[$k]['download_link_hdr_size'] = isset($zip_size['hdr']) ? (float)$zip_size['hdr'] : 0;
                }
            }

            $dataTmp['data'] = array_values($list);
            $this->redisService->set($keyLevelStory, $dataTmp);
        }
        return $dataTmp;
    }

    private function processFreeStory()
    {
        $year  = intval(date('Y', time()));
        $month = intval(date('m', time()));
        $day   = intval(date('d', time()));

        $keyStoryfree  = 'story_level_free___'.$this->os.$this->lang_id.$this->ver.$year.$month.$day;
        $dataStoryfree = $this->redisService->get($keyStoryfree, true);

        if (!$dataStoryfree) {
            $itemFreeStory = $this->_freeStoryRepository->getFreeStoryToDay();

            foreach ($itemFreeStory as $item) {
                $dataStoryfree[$item->story_lang_relate->lang_id][] = intval($item->slang_id);
            }

            $this->redisService->set($keyStoryfree, $dataStoryfree);
        }

        return $dataStoryfree;
    }
}
