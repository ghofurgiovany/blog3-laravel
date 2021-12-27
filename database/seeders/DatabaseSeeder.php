<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Child\Country;
use App\Models\Google\Keyword;
use App\Models\Image;
use App\Models\Post;
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
        $Country = Country::create([
            'name'  =>  'Indonesia',
            'iso2'  =>  'ID'
        ]);

        $author     =   Author::factory()->create();

        $keyword    =   $author->googleNewsKeyword()->create([
            'keyword'   =>  'bts',
            'language'  =>  'id'
        ])->refresh();

        $Country->keywords()->sync($keyword);

        return;

        Author::factory()
            ->has(
                Post::factory()
                    ->has(
                        Image::factory()->count(1)
                    )
                    ->has(
                        Category::factory()->count(2)
                    )
                    ->hasTags(10)
                    ->count(10)
            )
            ->hasImages()
            ->create();
    }
}
