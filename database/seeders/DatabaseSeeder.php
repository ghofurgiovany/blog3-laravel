<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Child\Country;
use App\Models\Google\Keyword;
use App\Models\Image;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name'          =>  'admin', 'password'      =>  Hash::make('admin123'), 'email'         =>  'admin@admin.com']);

        // Setting::create([
        //     [
        //         'key'   =>  'site_url',
        //         'value' =>  'https://news.geratekno.my.id'
        //     ],
        //     [
        //         'key'   =>  'facebook_active',
        //         'value' =>  '1'
        //     ],
        //     [
        //         'key'   =>  'facebook_client_id',
        //         'value' =>  '456646825805820'
        //     ],
        //     [
        //         'key'   =>  'facebook_secret',
        //         'value' =>  '320c845cf3c7583546129195df00a440'
        //     ],
        //     [
        //         'key'   =>  'google_news_max_age',
        //         'value' =>  '10'
        //     ]
        // ]);

        Author::factory()
            ->hasImages(1)
            ->create();

        Country::create([
            'name'  =>  'Indonesia',
            'iso2'  =>  'id'
        ]);

        Country::create([
            'name'  =>  'United States',
            'iso2'  =>  'us'
        ]);

        // Category::factory()->create();

        // return;

        // $Country = Country::create([
        //     'name'  =>  'Indonesia',
        //     'iso2'  =>  'ID'
        // ]);

        // $author     =   Author::factory()->create();

        // $keyword    =   $author->googleNewsKeyword()->create([
        //     'keyword'   =>  'bts',
        //     'language'  =>  'id'
        // ])->refresh();

        // $Country->keywords()->sync($keyword);

        // return;

        // $author  = Author::factory()->has(
        //     Post::factory()
        //         ->has(
        //             Image::factory()->count(1)
        //         )
        //         ->hasTags(5)
        //         ->count(20)
        // )
        //     ->hasImages(2)
        //     ->create();

        // $categories = Category::factory(20)->create();

        // $author->posts()->each(function ($post) use ($categories) {
        //     $post->categories()->sync($categories);
        // });

        // Author::factory()
        //     ->has(
        //         Post::factory()
        //             ->has(
        //                 Image::factory()->count(1)
        //             )
        //             ->has(
        //                 Category::factory()->count(2)
        //             )
        //             ->hasTags(10)
        //             ->count(200)
        //     )
        //     ->hasImages()
        //     ->create();
    }
}
