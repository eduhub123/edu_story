<?php
$router->group(
    ['middleware' => ['VerifyTokenServer']],
    function () use ($router) {
        // api app
    }
);


