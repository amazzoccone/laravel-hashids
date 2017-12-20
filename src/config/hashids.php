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

    /**
     * Custom configuration to override default configs
     * If whitelist key is true, it will convert all values (except specifies in blacklist)
     * If whitelist key is false, it will skip and not convert anything
     */
    'customizations' => [
        /*
        * Request header
        * */
        'header' => [
            'whitelist' => ['id'],
            'blacklist' => []
        ],

        /*
         * Request route parameters
         * */
        'route_parameters' => [
            'whitelist' => true,
            'blacklist' => ['session']
        ],

        /*
         * Request query parameters
         * */
        'query_parameters' => [
            'whitelist' => ['id'],
            'blacklist' => []
        ]
    ]
];