<?php

return [
    'system' => [
        'salt' => env('HASHIDS_SALT', 'Bondacom'),
        'length' => 12,
        'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    ],

    /*
     * Configuration to check if parameters should be decode/encode
     * */
    'default' => [
        'whitelist' => ['id'],
        'blacklist' => [],
        'separators' => ['_', '-']
    ],

    'customizations' => [
        /*
        * Custom configuration for request header (override default config)
        * */
        'header' => [
            'whitelist' => ['id'],
            'blacklist' => []
        ],

        /*
         * Custom configuration for request route parameters (override default config)
         * */
        'route_parameters' => [
            'whitelist' => ['id'],
            'blacklist' => []
        ],

        /*
         * Custom configuration for request query parameters (override default config)
         * */
        'query_parameters' => [
            'whitelist' => ['id'],
            'blacklist' => []
        ]
    ]
];