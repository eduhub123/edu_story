<?php

$db = [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'edu_story'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' =>
        [
            'edu_story' => [
                'read'        => [
                    'host'     => [
                        env('DB_SLAVE_1_HOST_STORY', env('DB_HOST_STORY', '0.0.0.0')),
                    ],
                    'port'     => env('DB_SLAVE_PORT_STORY', env('DB_PORT_STORY', 3306)),
                    'username' => env('DB_SLAVE_USERNAME_STORY', env('DB_USERNAME_STORY', '')),
                    'password' => env('DB_SLAVE_PASSWORD_STORY', env('DB_PASSWORD_STORY', '')),
                ],
                'write'       => [
                    'host'     => [
                        env('DB_MASTER_HOST_STORY', env('DB_HOST_STORY', '0.0.0.0'))
                    ],
                    'port'     => env('DB_MASTER_PORT_STORY', env('DB_PORT_STORY', 3306)),
                    'username' => env('DB_MASTER_USERNAME_STORY', env('DB_USERNAME_STORY', '')),
                    'password' => env('DB_MASTER_PASSWORD_STORY', env('DB_PASSWORD_STORY', '')),
                ],
                'driver'      => 'mysql',
                'database'    => env('DB_DATABASE_STORY', 'edu_story'),
                'unix_socket' => env('DB_SOCKET', ''),
                'charset'     => 'utf8',
                'collation'   => 'utf8_unicode_ci',
                'prefix'      => 'tbl_',
                'strict'      => false,
                'engine'      => null,
            ],

//            'mongodb' => [
//                'driver'   => 'mongodb',
//                'host'     => env('DB_HOST_MONGO', '127.0.0.1'),
//                'port'     => env('DB_PORT_MONGO', 27017),
//                'database' => env('DB_NAME_MONGO', ''),
//                'username' => env('USER_MONGO', ''),
//                'password' => env('PASS_MONGO', ''),
//                'options'  => [
//                    'database' => env('DB_NAME_MONGO') // sets the authentication database required by mongo
//                ]
//            ],

            'mongodb' => [
                'driver'   => 'mongodb',
                'dsn'      => env('DB_MONGO_URI', ''),
                'database' => env('DB_NAME_MONGO', ''),
            ],

        ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],
    ],

];

return $db;
