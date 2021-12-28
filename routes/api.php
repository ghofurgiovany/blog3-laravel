<?php

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/private/paths', fn () => Post::get(['slug']));
Route::get('/private/paths/tag', fn () => Tag::get(['slug']));

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
