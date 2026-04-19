<?php

return [
    'account_id' => env('ZOOM_ACCOUNT_ID'),
    'client_id' => env('ZOOM_CLIENT_ID'),
    'client_secret' => env('ZOOM_CLIENT_SECRET'),
    'host_user_id' => env('ZOOM_HOST_USER_ID'),
    'timezone' => env('ZOOM_DEFAULT_TIMEZONE', 'Asia/Colombo'),
    'waiting_room' => env('ZOOM_WAITING_ROOM', true),
];
