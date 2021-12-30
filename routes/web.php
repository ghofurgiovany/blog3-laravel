<?php

use App\Http\Controllers\FacebookController;
use App\Models\Facebook\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Noweh\TwitterApi\Client;

Route::get('/test', function () {

    if (!(bool) \setting('twitter_active')) {
        return;
    }

    $post       =   Post::with(['tags' => fn ($q) => $q->limit(3)])->find(1);
    $tags       =   collect($post->tags)->map(fn ($p) => "#" . preg_replace("/\s/", "_", $p->name))->toArray();
    $tags       =   implode(" ", $tags);

    $settings   = [
        'account_id' => \setting('twitter_account_id'),
        'consumer_key' => \setting('twitter_consumer_key'),
        'consumer_secret' => \setting("twitter_consumer_secret"),
        'bearer_token' => \setting('twitter_bearer_token'),
        'access_token' => \setting("twitter_access_token"),
        'access_token_secret' => \setting('twitter_access_token_secret')
    ];

    $tweet  =   trim(
        strlen($post->description) > 150
            ? substr($post->description, 0, 150)
            : $post->description
    )  .
        "...\n\n" .
        $tags . "\n\n" .
        "Read more at: " . \setting("site_url") . '/' . $post->slug;

    if (strlen($tweet) > 280) {
        $tweet = trim($post->title)  . "\n\nRead more at: " . \setting("site_url") . '/' . $post->slug;
    }

    $client = new Client($settings);
    $client->tweet()->performRequest('POST', [
        'text' => $tweet
    ]);

    return [];
});

Route::get('/auth/facebook', [FacebookController::class, 'auth']);
Route::get('/auth/facebook/callback', [FacebookController::class, 'callback']);

Route::get('/', function () {
    return view('welcome');
});
