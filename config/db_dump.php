<?php

return [

    /**
     * Specify connections to dump from database config
     */
    'connections' => [env('DB_CONNECTION')],

    /**
     * Below you can use full name like: connection.db.table
     * To include or exclude some tables
     *
     * Please note that if you using regular expression wrap it by # like: '#^mysql\.#'
     */
    'white-list' => [ //include only tables listed here

    ],
    'black-list' => [ //exclude tables listed here
        '#.*\.information_schema\..*#',
        '#.*\.performance_schema\..*#',
        '#.*\.mysql\..*#',
        '#.*\.sys\..*#',
    ],

    /**
     * Directory to store dumps
     */
    'directory' => storage_path('database/dumps'),
];
