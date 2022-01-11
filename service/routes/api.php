<?php
$router->group(
    ['middleware' => ['VerifyTokenServer']],
    function () use ($router) {
        // api app
        $router->post('get-list-story-by-id', 'App\StoryLangController@getListById');
    }
);


