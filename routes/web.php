<?php

use App\Http\Controllers\FacebookController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return \getThumbnail('https://news.geratekno.my.id/_next/image?url=https%3A%2F%2Fres.cloudinary.com%2Fhcguyiabx%2Fimage%2Fupload%2Fv1641039055%2Ffa6tfeuuaosue7we9o2h.jpg&w=3840&q=75', true);
});

Route::get('/auth/facebook', [FacebookController::class, 'auth']);
Route::get('/auth/facebook/callback', [FacebookController::class, 'callback']);

Route::get('/', function () {
    return view('welcome');
});
