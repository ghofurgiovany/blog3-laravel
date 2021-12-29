<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

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

    public function feeds(Request $request)
    {
        if (!$request->q) {
            $posts      =   Post::paginate(50);
        } else {
            $posts      =   Category::where('name', $request->q);
            if ($posts->exists()) {
                $posts  =   $posts->first()->posts()->paginate(50);
            } else {
                $posts  =   Post::paginate(50);
            }
        }

        return $posts->getCollection()
            ->map(fn ($post) => [
                'created_at'        =>  $post->created_at,
                'updated_at'        =>  $post->updated_at,
                'slug'              =>  $post->slug,
                'title'             =>  $post->title,
                'description'       =>  $post->description,
                'paragraph'         =>  \gettype($post->paragraph) === 'array' && isset($post->paragraph[0]) ? $post->paragraph[0] : '',
                'thumbnail'            =>  $post->thumbnail,
                'categories'        =>  $post->categories
            ]);
    }
}
