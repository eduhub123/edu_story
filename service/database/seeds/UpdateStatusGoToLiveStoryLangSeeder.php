<?php

use App\Repositories\Story\StoryLangRepository;
use App\Models\Story\StoryLang;
use Illuminate\Database\Seeder;

class UpdateStatusGoToLiveStoryLangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $storyLangRepository;

    public function __construct(
        StoryLangRepository $storyLangRepository
    ) {
        $this->storyLangRepository = $storyLangRepository;
    }

    public function run()
    {
        $this->storyLangRepository->updateByOneField('go_to_live', 1, ['go_to_live' => StoryLang::GO_TO_LIVE]);
        echo "done";
    }
}
