<?php

// config for Dmgroup/PrSfmc
return [
    'api' => [
        'token' => env('SFMC_TOKEN','xxx'),
        'host' => env('SFMC_HOST','https://api.pernod-ricard.io/staging/v3'),
    ],
    'brand' => env('SFMC_BRAND', 'xxx'),
    'prEntity' => env('SFMC_ENTITY', 'xxx'),
    'touchPointName' => env('SFMC_TOUCHPOINT_NAME', 'xxx'),
    'activityName' => env('SFMC_ACTIVITY_NAME', 'activityName'),
    'activityType' => env('SFMC_ACTIVITY_TYPE', 'activityType'),
    'activityId' => env('SFMC_ACTIVITY_ID', 'activityId'),
    'transmittable_type' => env('SFMC_TRANSMITTABLE_TYPE', 'App\Models\Subscriber')
];
