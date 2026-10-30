<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dbcheck', function () {
    try {
        \DB::connection()->getPdo();
        return 'DB connected: ' . \DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return 'DB failed: ' . $e->getMessage();
    }
});
