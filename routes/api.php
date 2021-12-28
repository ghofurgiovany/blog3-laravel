<?php

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/ping/{post}', fn (Post $post) => $post->increment('views') ? [] : []);
Route::get('/private/paths', fn () => Post::with(['countries'])->get(['slug', 'language']));
Route::get('/private/paths/tag', fn () => Tag::get(['slug']));

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
