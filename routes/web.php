<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Models\Google\Generated;
use App\Models\Google\Keyword;
use App\Models\Post;
use Illuminate\Support\Facades\Http;

class Jobs
{
    public function __construct(string $link, Keyword $keyword)
    {
    }
}


Route::get('/test', function () {
    new Jobs('https://www.hindustantimes.com/entertainment/music/bts-v-sings-along-knockin-on-heaven-s-door-while-rm-jams-to-it-members-tease-jungkook-watch-101640513487443.html', Keyword::find(1));
});

Route::get('/', function () {
    return view('welcome');
});
