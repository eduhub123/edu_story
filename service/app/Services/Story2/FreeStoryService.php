<?php

namespace App\Services\Story2;

use App\Models\Language;
use App\Models\Story2\FreeStory;
use App\Repositories\Story2\FreeStoryRepository;

class FreeStoryService
{
    private $freeStoryRepos;

    public function __construct(
        FreeStoryRepository $freeStoryRepos
    ) {
        $this->freeStoryRepos = $freeStoryRepos;
    }

    public function getFreeStories($appId, $type, $time)
    {
        $data        = [];
        $freeStories = $this->freeStoryRepos->getFreeStory($appId, $type, $time)->toArray();
        foreach ($freeStories as $freeStory) {
            $idLanguage          = Language::getIdLanguageByIdApp($freeStory[FreeStory::_ID_APP]);
            $data[$idLanguage][] = $freeStory[FreeStory::_ID_STORY];
        }
        return $data;
    }
}
