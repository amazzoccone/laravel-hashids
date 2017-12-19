<?php

return [
    'salt' => env('HASHIDS_SALT', 'Bondacom'),
    'length' => 12,

    /*
     * Configuration to check if parameters should be decode/encode
     * */
    'keys' => [
        'whitelist' => ['id'],
        'blacklist' => []
    ]
];