<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
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
        // \App\Models\User::factory(10)->create();

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
