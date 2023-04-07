<?php

use App\Models\Globals\ListApp;
use App\Models\Language;
use App\Models\Story2\StoryLang;
use Illuminate\Database\Seeder;

class MigrateStoryTutoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ids = "4074,4073,4072,4075,4080,4094,4082,4076,4085,4098,4096,4077,4070,4086,4104,4079,4083,4095,4088,4089,4103,4090,4093,4102,4078,4092,4091,4071,4101,4099,4087,4084,4097,4100,4081,4105,4106,4107";
        StoryLang::whereIn(StoryLang::_ID_STORIES, explode(",", $ids))
            ->where(StoryLang::_ID_APP, ListApp::APP_ID_MS_EN)
            ->where(StoryLang::_ID_LANGUAGES, Language::ID_LANG_EN)
            ->update([
                StoryLang::_ID_APP => ListApp::APP_ID_TUTORING_NATIVE
            ]);
    }
}
