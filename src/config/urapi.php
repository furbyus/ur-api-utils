<?php


/*
|--------------------------------------------------------------------------
| Application Configuration - General Info For Ur-API
|--------------------------------------------------------------------------
|
| Register here the info relative to the app, and which api implements
|
*/



return [
    "general"=>[
        "api" => [
            "name" => env("APP_API_NAME", "ElectryNet"),
            "version" => env("APP_API_VERSION", "1.0"),
        ],
        "app" => [
            "name" => env("APP_NAME", "Heimdall"),
            "version" => env("APP_VERSION", "1.0"),
        ],
    ],
    
];