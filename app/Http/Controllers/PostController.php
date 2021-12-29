<?php

namespace App\Http\Controllers;

use App\Models\Post;
use DB;

class PostController extends Controller
{
    public function sitemapCount()
    {
        return [
            'lastPage'  =>  Post::paginate(
                (int) \setting('sitemap_count', 500)
            )->lastPage()
        ];
    }

    public function sitemap()
    {
        return Post::paginate(500, ['updated_at', 'created_at', 'slug'])
            ->getCollection()
            ->map(fn ($post) => [
                'created_at'    =>  $post->created_at,
                'updated_at'    =>  $post->updated_at,
                'slug'          =>  $post->slug
            ]);
    }
}