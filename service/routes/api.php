<?php
$router->group(
    ['middleware' => ['VerifyTokenServer']],
    function () use ($router) {
        // api app
        // *
        $router->post('get-list-story-by-id', 'App\StoryLangController@getListById');

        //1
        $router->get('get-all-level-story', 'App\StoryLangController@getAllLevelStory');

        //2
        $router->get('get-category-story', 'App\StoryLangController@getCategoryStory');

        //3
        $router->get('get-all-language-use', 'App\StoryLangController@getAllLanguageUse');

        //4
        $router->get('get-list-grade-story', 'App\StoryLangController@getListGradeStory');

        //5
        $router->get('get-list-description-story', 'App\StoryLangController@getListDescriptionStory');

        $router->get('get-story-free', 'App\StoryLangController@getStoryFree');

        //*
        $router->post('get-list-story-by-list-id', 'App\StoryLangController@getListStory');

        $router->post('zip-first-install-ms', 'App\FirstInstallController@zipFirstInstallMS');

        //*
        $router->get('get-list-free', 'App\FreeController@getListFree');

        //6
        $router->get('get-version-audio-book', 'App\AudioBookController@getVersionAudioBook');

        //7
        $router->get('get-version-worksheet', 'App\WorksheetController@getVersionWorksheet');
    }
);


