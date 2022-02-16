<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Noweh\TwitterApi\Client;

class TwitterPost implements ShouldQueue
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
        $this->post->load(['tags' => fn ($tags) => $tags->limit(3)]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!(bool) \setting('twitter_active')) {
            return;
        }

        $post       =   $this->post;
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
    }
}
