<?php

namespace App\Http\Controllers\App\v2;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Story2\StoryLang;
use App\Repositories\Story2\StoryLangRepository;
use App\Services\Story2\StoryService;
use Illuminate\Http\Request;

class StoryLangController extends Controller
{

    private $storyLangRepos;
    private $storyService;
    private $request;

    public function __construct(
        StoryLangRepository $storyLangRepos,
        StoryService $storyService,
        Request $request
    ) {
        $this->storyLangRepos = $storyLangRepos;
        $this->storyService   = $storyService;
        $this->request        = $request;
    }

    public function getListStory()
    {
        $data        = [];
        $idStoryLang = $this->request->input('slang_id');

        if (!$idStoryLang) {
            $this->message = __('app.invalid_params');
            goto next;
        }

        $storiesLang = $this->storyLangRepos->getListByListId($idStoryLang)->keyBy(StoryLang::_ID_STORY_LANG)->toArray();
        foreach ($storiesLang as $idStoryLang => $storyLang) {
            $data[$idStoryLang] = [
                'slang_id'      => $storyLang[StoryLang::_ID_STORY_LANG],
                'sid'           => $storyLang[StoryLang::_ID_STORIES],
                'lang_id'       => $storyLang[StoryLang::_ID_LANGUAGES],
                'name'          => $storyLang[StoryLang::_NAME],
                'zip_size'      => $storyLang[StoryLang::_ZIP_SIZE],
                'version_story' => $storyLang[StoryLang::_VERSION_STORY],
            ];
        }
        $this->status  = 'success';
        $this->message = __('app.success');
        next:
        return $this->responseData($data);
    }

    public function getVersionStory()
    {
        $idApp = $this->request->input('id_app');

        $idLanguage  = Language::getIdLanguageByIdApp($idApp);
        $lastVersion = $this->storyService->getLastVersionStory($idApp, $idLanguage);

        $this->status  = 'success';
        $this->message = __('app.success');
        return $this->responseData($lastVersion);
    }
}
