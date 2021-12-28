<?php

namespace App\Jobs;

use App\Models\Facebook\Page;
use App\Models\Post;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class PostFacebook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post     =   $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $categories   = $this->post->categories()->get(['category_id'])->map(fn ($c) => $c->category_id);
        $pages        = Page::where(['active' => true])->whereHas('categories', function ($query) use ($categories) {
            return $query->whereIn('category_id', $categories);
        })->get();

        $articleRelativeUrl =   \setting('site_url', 'https://news.geratekno.my.id') . '/' . $this->post->slug;
        $failed             =   [];

        foreach ($pages as $page) {
            $response   =   Http::post('https://graph.facebook.com/' . $page->page_id . '/feed?' . \http_build_query([
                'message'       =>  $this->post->description . "\n\nRead more at " . $articleRelativeUrl,
                'link'          =>  $articleRelativeUrl,
                'access_token'  =>  $page->access_token
            ]));

            if ($response->failed()) {
                $failed[] = $page->name;
            }

            $page->posts()->sync($this->post);
        }

        if ($failed) {
            throw new Exception("Error on page: " . \json_encode($failed, \JSON_PRETTY_PRINT), 1);
        }
    }
}
