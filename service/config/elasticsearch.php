<?php

return
[
    'host'       => env('ELASTICSEARCH_HOST', '127.0.0.1'),
    'port'       => env('ELASTICSEARCH_PORT', 9200),
    'scheme'     => env('ELASTICSEARCH_SCHEME', null),
    'user'       => env('ELASTICSEARCH_USER', null),
    'pass'       => env('ELASTICSEARCH_PASS', null),
];
