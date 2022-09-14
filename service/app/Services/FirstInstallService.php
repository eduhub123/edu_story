<?php

namespace App\Services;

use App\Models\Globals\ListApp;
use App\Models\LangDisplay;
use App\Models\Language;
use App\Models\Story2\PopularSearch;
use App\Models\Story2\Worksheet;
use App\Services\Globals\ListAppService;
use App\Services\Globals\ProductService;
use App\Services\Platform\GameService;
use App\Services\ServiceConnect\LessonConnectService;
use App\Services\ServiceConnect\MediaConnectService;
use App\Services\Story2\PopularSearchService;
use App\Services\Story2\WorksheetService;
use App\Services\Story2\AudioBookService;
use App\Services\Story2\CategoryService;
use App\Services\Story2\GradeService;
use App\Services\Story2\LevelService;
use App\Services\Story2\StoryService;
use App\Services\Support\ProblemService;
use Illuminate\Support\Facades\Config;

class FirstInstallService
{
    const FOLDER_UPLOAD_FIRST_INSTALL = 'App/uploads/install';
    const COUPON_CODE_STORY           = 'STORIES';

    private $listAppService;
    private $productService;
    private $gameService;
    private $problemService;
    private $audioBookService;
    private $storyService;
    private $levelService;
    private $gradeService;
    private $categoryService;
    private $popularSearchService;
    private $worksheetService;
    private $lessonConnectService;
    private $mediaConnectService;

    public function __construct(
        ListAppService $listAppService,
        ProductService $productService,
        GameService $gameService,
        ProblemService $problemService,
        AudioBookService $audioBookService,
        StoryService $storyService,
        LevelService $levelService,
        GradeService $gradeService,
        CategoryService $categoryService,
        PopularSearchService $popularSearchService,
        WorksheetService $worksheetService,
        LessonConnectService $lessonConnectService,
        MediaConnectService $mediaConnectService
    ) {
        $this->listAppService = $listAppService;
        $this->productService = $productService;
        $this->gameService    = $gameService;

        $this->problemService = $problemService;

        $this->audioBookService     = $audioBookService;
        $this->storyService         = $storyService;
        $this->levelService         = $levelService;
        $this->gradeService         = $gradeService;
        $this->categoryService      = $categoryService;
        $this->popularSearchService = $popularSearchService;
        $this->worksheetService     = $worksheetService;
        $this->lessonConnectService = $lessonConnectService;
        $this->mediaConnectService  = $mediaConnectService;
    }

    public function getDataFileFirstInstallMS($idApp, $deviceType, $os, $isLicence, $detectUrlCluster, $version, $isInHouse, $dataVersion)
    {
        $idLanguage    = Language::getIdLanguageByIdApp($idApp);
        $idLangDisplay = LangDisplay::getIdLangDisplayByIdApp($idApp);

        $data = [];
        //app
        $data = $this->getInfoApp($data, $idApp, $isLicence, $detectUrlCluster);
        //game
        $data['game_list'] = $this->gameService->getDataListGameMS($idApp, 0, 0);
        //product
        $data['product_list'] = $this->productService->listProductByApp($idApp);
        //popularSearch
        $popularSearch = $this->popularSearchService->getPopularSearchV2($idApp, [PopularSearch::POPULAR_AUDIO, PopularSearch::POPULAR_STORY, PopularSearch::POPULAR_WORKSHEET_PHONIC, PopularSearch::POPULAR_WORKSHEET_STORY]);
        $dataPopularSearch = [];
        foreach ($popularSearch as $item) {
            if ($item[PopularSearch::_TYPE] == PopularSearch::POPULAR_AUDIO) {
                $dataPopularSearch[PopularSearch::POPULAR_AUDIO][] = $item;
            } elseif ($item[PopularSearch::_TYPE] == PopularSearch::POPULAR_STORY) {
                $dataPopularSearch[PopularSearch::POPULAR_STORY][] = $item;
            } elseif ($item[PopularSearch::_TYPE] == PopularSearch::POPULAR_WORKSHEET_PHONIC || $item[PopularSearch::_TYPE] == PopularSearch::POPULAR_WORKSHEET_STORY) {
                $dataPopularSearch[PopularSearch::POPULAR_WORKSHEET_PHONIC][] = $item;
            }
        }

        //audioBook
        list($audioBooks, $delete) = $this->audioBookService->processDataAudioBook($idApp, $idLanguage, $version, $dataVersion['version_audio'] ?? 0, $isInHouse);
        $audioItem['list_audio_book'] = array_values($audioBooks);
        $audioItem['delete']          = array_values($delete);
        $audioItem['version_audio']   = $dataVersion['version_audio'] ?? 0;
        $audioItem['popular_search']  = $dataPopularSearch[PopularSearch::POPULAR_AUDIO] ?? [];
        $audioItem['Series']          = $this->audioBookService->getDataSeries($idApp, $idLanguage, $idLangDisplay, $dataVersion['version_audio'] ?? 0, $isInHouse);
        $data['audio']                = $audioItem;

        //story
        list($stories, $deleteStory) = $this->storyService->processDataStory($idApp, $deviceType, $idLanguage, 0, $version, $dataVersion['version_story'] ?? 0, $isInHouse);
        $storyItem['story']          = array_values($stories);
        $storyItem['delete']         = array_values($deleteStory);
        $storyItem['version_story']  = $dataVersion['version_story'] ?? 0;
        $storyItem['popular_search'] = $dataPopularSearch[PopularSearch::POPULAR_STORY] ?? [];
        list($levelIds, $dataLevels) = $this->levelService->getListLevel($idApp);
        $storyItem['all_level'] = $levelIds;
        $storyItem['level']     = $dataLevels;
        $storyItem['category']  = $this->categoryService->getListCategory($idApp);
        $storyItem['grade']     = $this->gradeService->getListGrade($idApp);
        $data['story']          = $storyItem;


        //dataMonkeyTalking
        $data['ai_speaking'] = $this->getDataMonkeyTalking($idApp, $deviceType, $version);
        //categories
        $data['categories_list'] = $this->lessonConnectService->getListCategory($idApp, $deviceType, $version);
        //lesson
        $data['lesson_list']   = $this->lessonConnectService->getListLesson($idApp, $deviceType, $version);
        $data['list_activity'] = $this->getListActivities($data['story'], $data['lesson_list'], $idApp, $deviceType, $os, $version, $isInHouse);
        //worksheet
        $lessons =  [];
        if(isset($data['lesson_list']['list'])) {
            foreach ($data['lesson_list']['list'] as $lesson){
                $lessons[$lesson['id']] = $lesson;
            }
        }
        $worksheetItem                       = $this->worksheetService->getDataWorksheet($idApp, $idLanguage, $version, $deviceType, $isInHouse, $stories, $lessons);
        $worksheetItem['popular_search']     = $dataPopularSearch[PopularSearch::POPULAR_WORKSHEET_PHONIC] ?? [];
        $worksheetItem['max_worksheet_send'] = Worksheet::CONFIG_SEND;
        $worksheetItem['version_worksheet']  = $dataVersion['version_worksheet'] ?? 0;;
        $data['worksheet'] = $worksheetItem;

        $data['download_thumb_story']  = 'App/zip/' . $deviceType . '/story/story_ms.zip';
        $data['download_thumb_lesson'] = 'App/zip/' . $deviceType . '/lesson/lesson_v2.zip';
        $data['download_thumb_audio']  = 'App/zip/' . $deviceType . '/audiobook/audiobook_ms.zip';
        $data['download_thumb_other']  = 'App/zip/' . $deviceType . '/other/other_v2.zip';
        return $data;
    }

    private function getDataMonkeyTalking($idApp, $deviceType, $subversion)
    {
        $data['speakingTopicList'] = $this->lessonConnectService->getListCategory($idApp, $deviceType, $subversion, LessonConnectService::MONKEY_TALKING);
        $listLessonMonkeyTaking    = $this->lessonConnectService->getListLessonMonkeyTalking($idApp, $deviceType, $subversion);
        $data['lessonList']        = $listLessonMonkeyTaking;
        $listIdLesson              = isset($listLessonMonkeyTaking['list']) ? array_column($listLessonMonkeyTaking['list'], 'id') : [];
        $data['itemList']          = $this->lessonConnectService->getListActLesson($idApp, $deviceType, $subversion, $listIdLesson);
        $data['common_mk_talking'] = $this->lessonConnectService->getDataCommonMkTalking($idApp);
        return $data;
    }

    private function getInfoApp($data, $idApp, $isLicence, $detectUrlCluster)
    {
        $infoApp          = $this->listAppService->getAppByIdApp($idApp);
        $data['app_id']   = $infoApp[ListApp::_ID] ?? $idApp;
        $data['app_name'] = $infoApp[ListApp::_INFO] ?? "";
        $data['app_ver']  = $infoApp[ListApp::_APP_VERSION] ?? 0;

        $data['is_licence'] = $isLicence;
        $data['use_url']    = $detectUrlCluster;

        $data['pay_use_url']      = $this->getLinkPay($idApp);
        $data['redirect_uri']     = $this->urlSignInAccountKit();
        $data['coupon_code']      = self::COUPON_CODE_STORY;
        $data['lang']             = array_values(LangDisplay::LIST_LANG_DISPLAY_APP);
        $data['list_app_problem'] = $this->problemService->getListAppProblem();
        return $data;
    }

    private function getLinkPay($idApp)
    {
        switch ($idApp) {
            case ListApp::APP_ID_LTR:
                return Config::get('path.URL_PAY_MONKEYJUNIOR');
            case ListApp::APP_ID_MS_EN:
                return Config::get('path.URL_PAY_STORIES');
            case ListApp::APP_ID_MM:
                return Config::get('path.URL_PAY_MONKEYMATH');
            case ListApp::APP_ID_MS_VN:
                return Config::get('path.URL_PAY_VMONKEY');
            default:
                return '';
        }
    }

    private function urlSignInAccountKit()
    {
        return Config::get('domainWeb.redirect_uri');
    }

    private function getListActivities($listStory, $listLesson, $idApp, $deviceType, $os, $subversion, $isInHouse)
    {
        $listStoryId   = isset($listStory['story']) ? array_column($listStory['story'], 'id') : [];
        $listLessonId  = isset($listLesson['list']) ? array_column($listLesson['list'], 'id') : [];
        $listActStory  = $this->lessonConnectService->getListActStory($idApp, $deviceType, $subversion, $listStoryId, $isInHouse, $os);
        $listActLesson = $this->lessonConnectService->getListActLesson($idApp, $deviceType, $subversion, $listLessonId);
        return array_merge($listActStory, $listActLesson);
    }

    public function zipFileFirstInstallMS($idApp, $deviceType, $os, $subversion, $isInHouse, $dataVersion, $dataFirstInstall)
    {
        $keyFileName['env']                        = env('APP_ENV');
        $keyFileName['version_story']              = $dataVersion['version_story'] ?? 0;
        $keyFileName['version_audio']              = $dataVersion['version_audio'] ?? 0;
        $keyFileName['version_worksheet']          = $dataVersion['version_worksheet'] ?? 0;
        $keyFileName['version_game']               = $dataVersion['version_game'] ?? 0;
        $keyFileName['version_lesson']             = $dataVersion['version_lesson'] ?? 0;
        $keyFileName['version_categories']         = $dataVersion['version_categories'] ?? 0;
        $keyFileName['version_lesson_talking']     = $dataVersion['version_lesson_talking'] ?? 0;
        $keyFileName['version_categories_talking'] = $dataVersion['version_categories_talking'] ?? 0;
        $keyFileName['version_common_mk_talking']  = $dataVersion['version_common_mk_talking'] ?? 0;
        $keyFileName['device_type']                = $deviceType;
        $keyFileName['subversion']                 = $subversion;
        $keyFileName['os']                         = $os;
        $keyFileName['in_house']                   = $isInHouse;

        return $this->zipFileFirstInstall("first_install_v2", $keyFileName, $dataFirstInstall);
    }

    public function zipFileFirstInstall($name, $keyFileName, $dataFirstInstall)
    {
        $fileName = $name;
        foreach ($keyFileName as $key => $value) {
            $fileName .= '_' . $value;
        }
        $pathFile   = UploadService::zipFirstInstall('install', $fileName, $dataFirstInstall);
        $fileUpload = UploadService::getFile($pathFile);
        $response   = $this->mediaConnectService->upload($fileUpload, self::FOLDER_UPLOAD_FIRST_INSTALL);
        if (isset($response['data']['link'])) {
            return $response['data']['link'];
        }
        return '';
    }
}
