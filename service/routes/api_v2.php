<?php
//api word
$router->get('story/list', 'v2\StoryController@list');

$router->get('audiobook/list', 'v2\AudioBookController@getAudioBook');

$router->group(
    ['middleware' => ['VerifyTokenApp']],
    function () use ($router) {
    }
);
