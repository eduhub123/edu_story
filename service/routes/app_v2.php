<?php
$router->group(
    ['middleware' => ['VerifyTokenServer']],
    function () use ($router) {
        // api app
        $router->post('get-list-story-by-list-id', 'App\v2\StoryLangController@getListStory');

        $router->get('get-version-story', 'App\v2\StoryLangController@getVersionStory');
    }
);


