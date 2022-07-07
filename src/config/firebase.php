<?php

return [
    'firebase_config_v1' => [
        'apiKey' => env('FCM_API_KEY'),
        'authDomain' => env('FCM_AUTH_DOMAIN'),
        'projectId' => env('FCM_PROJECT_ID'),
        'storageBucket' => env('FCM_STORAGE_BUCKET'),
        'messagingSenderId' => env('FCM_MESSAGIN_SENDER_ID'),
        'appId' => env('FCM_APP_ID')
    ],
    'fcm_api_url' => "https://fcm.googleapis.com/v1/projects/". env('FCM_PROJET_ID') . "/messages:send"
];
