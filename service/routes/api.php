<?php
$router->group(
    ['middleware' => ['VerifyTokenServer']],
    function () use ($router) {
        // api app
        $router->post('get-list-story-by-id', 'App\StoryLangController@getListById');

        $router->get('get-all-level-story', 'App\StoryLangController@getAllLevelStory');

        $router->get('get-category-story', 'App\StoryLangController@getCategoryStory');

        $router->get('get-all-language-use', 'App\StoryLangController@getAllLanguageUse');

        $router->get('get-list-grade-story', 'App\StoryLangController@getListGradeStory');

        $router->get('get-list-description-story', 'App\StoryLangController@getListDescriptionStory');
    }
);


