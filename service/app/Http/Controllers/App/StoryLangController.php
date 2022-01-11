<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Story\StoryLang;
use App\Repositories\Story\StoryLangRepository;
use Illuminate\Http\Request;

class StoryLangController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $storyLangRepos;
    private $request;

    public function __construct(
        StoryLangRepository $storyLangRepos,
        Request $request
    ) {
        $this->storyLangRepos = $storyLangRepos;
        $this->request        = $request;
    }

    public function getListById()
    {
        $data        = [];
        $listStoryId = $this->request->input('story_id');
        $langId      = $this->request->input('lang_id');

        if (!$listStoryId) {
            $this->message = __('app.invalid_params');
            goto next;
        }

        $data = $this->storyLangRepos->getListById($listStoryId, $langId)->groupBy(StoryLang::_SLANG_ID);

        $this->status  = 'success';
        $this->message = __('app.success');


        next:
        return $this->responseData($data);
    }

}
