<?php

use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Abraham\TwitterOAuth\TwitterOAuth;

Route::post('/ping/{post}', fn (Post $post) => $post->increment('views') ? [] : []);
Route::get('/private/paths', fn () => Post::with(['countries'])->get(['slug', 'language']));
Route::get('/private/paths/tag', fn () => Tag::get(['slug']));
Route::get('/paths/v2/sitemap/count', [PostController::class, 'sitemapCount']);
Route::get('/paths/v2/sitemap', [PostController::class, 'sitemap']);
Route::get('/paths/v2/rss', [PostController::class, 'feeds']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
