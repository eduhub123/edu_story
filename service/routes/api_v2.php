<?php
//api word
$router->get('story/list', 'v2\StoryController@list');

$router->get('story/list_vn', 'v2\StoryController@listVM');

$router->get('audiobook/list', 'v2\AudioBookController@getAudioBook');

$router->get('audiobook/list_vn', 'v2\AudioBookController@listVM');

$router->get('audiobook/detail', 'v2\AudioBookController@detail');

$router->get('worksheet/list', 'v2\WorksheetController@getListWorksheet');

$router->group(
    ['middleware' => ['VerifyTokenApp']],
    function () use ($router) {
    }
);
