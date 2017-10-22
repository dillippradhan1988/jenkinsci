<?php
    return [
        'settings' => [
            'displayErrorDetails'       => true, // set to false in production

            'addContentLengthHeader'    => false, // Allow the web server
            //to send the content-length header

            // Renderer settings
            'view'        => [
                'templatePath' => __DIR__ . '/../templates/',
            ],

            // Monolog settings
            'logger'      => [
                'name'          => 'slim-app',
                'path'          => __DIR__ . '/../logs/app.log',
                'level'         => \Monolog\Logger::DEBUG,
            ],

            // Doctrine settings
            'doctrine'    => [
                'connection'    => [
                    'driver'        => 'pdo_mysql',
                    'host'          => '172.17.0.3',
                    //'port'          =>  '3306'
                    'dbName'        => 'todo',
                    'user'          => 'root',
                    'password'      => 'test1234',
                ]
            ]
        ],
    ];