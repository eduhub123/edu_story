<?php


namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Story2\FreeStory;
use App\Repositories\Story2\FreeStoryRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;


class FreeController extends Controller
{
    private $request;
    private $freeStoryRepos;

    public function __construct(
        Request $request,
        FreeStoryRepository $freeStoryRepos
    ) {
        $this->request        = $request;
        $this->freeStoryRepos = $freeStoryRepos;
    }

    public function getListFree()
    {
        $idApp = $this->request->input('id_app');
        $type  = $this->request->input('type');
        $time  = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;

        $listFreeStory = $this->freeStoryRepos->getFreeStory($idApp, $type, $time)->toArray();

        $data = [];
        foreach ($listFreeStory as $freeStory) {
            $idLanguage = Language::getIdLanguageByIdApp($freeStory[FreeStory::_ID_APP]);
            if ($type) {
                $data[$idLanguage][] = $freeStory[FreeStory::_ID_STORY];
            } else {
                $data[$freeStory[FreeStory::_TYPE]][$idLanguage][] = $freeStory[FreeStory::_ID_STORY];
            }
        }

        $this->status  = 'success';
        $this->message = __('app.success');
        next:
        return $this->responseData($data);
    }

}
