<?php

namespace App\GraphQL\Queries;

use App\Models\Post;
use DB;

class PostQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }

    public function related($_, array $args)
    {
        $categories =   $_->categories()
            ->inRandomOrder()
            ->limit(2)
            ->get();

        $posts      =   [];

        foreach ($categories as $category) {
            $postsId            =   \collect($posts)->map(fn ($post) => $post->id);
            $postCollection     =   $category
                ->posts()
                ->inRandomOrder()
                ->orderBy('views', 'DESC')
                ->whereNotIn('posts.id', $postsId)
                ->limit(5)
                ->get();

            foreach ($postCollection as $post) {
                $posts[] = $post;
            }
        }

        return $posts;
    }
}
