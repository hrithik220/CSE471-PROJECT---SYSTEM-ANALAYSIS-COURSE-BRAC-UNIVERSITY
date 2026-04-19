<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Maps API Key
    |--------------------------------------------------------------------------
    | BUG FIX: MapController was calling env('GOOGLE_MAPS_API_KEY') directly,
    | which breaks when config is cached. The correct Laravel pattern is to
    | read from config(). This file wires the .env variable into config().
    |
    | Set in .env:
    |   GOOGLE_MAPS_API_KEY=your_key_here
    */
    'google_maps' => [
        'key' => env('GOOGLE_MAPS_API_KEY', ''),
    ],
];
