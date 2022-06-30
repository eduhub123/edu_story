<?php
//api word
$router->get('story/list', 'v1\StoryController@list');


$router->group(
    ['middleware' => ['VerifyTokenApp']],
    function () use ($router) {
        $router->get('story/trial-items', 'v1\StoryController@getTrialItems');
    }
);

$router->get('demo', 'TestController@demo');

$router->get('/debug-sentry', function () {
    throw new Exception('My first Sentry error story');
});

