<?php

use App\Http\Controllers\FacebookController;
use App\Models\Facebook\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/auth/facebook', [FacebookController::class, 'auth']);
Route::get('/auth/facebook/callback', [FacebookController::class, 'callback']);

Route::get('/', function () {
    return view('welcome');
});
