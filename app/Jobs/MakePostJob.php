<?php

namespace App\Jobs;

use App\Models\Facebook\Page;
use App\Models\Google\Generated;
use App\Models\Google\Keyword;
use App\Models\Image;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class MakePostJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $link;
    public $keyword;

    public function __construct(string $link, Keyword $keyword)
    {
        $this->link     =   $link;
        $this->keyword  =   $keyword;
    }

    public function handle()
    {
        $keyword        = $this->keyword;
        $articleSource  = Http::get($this->link);
        $generated      = Generated::where([
            'link'  =>  $this->link
        ]);

        if ($articleSource->failed()) {
            $generated->update([
                'status'  =>  'failed'
            ]);

            throw new Exception("Error Processing Request", $articleSource->getStatusCode());
        }

        $articleSource  =   $articleSource->body();
        $articleDOM     =   new DOMDocument();
        @$articleDOM->loadHTML($articleSource);
        $xPath          =   new DOMXPath($articleDOM);

        $title          =   $xPath->query('//meta[@property="og:title"]/@content')->item(0);
        $description    =   $xPath->query('//meta[@property="og:description"]/@content')->item(0);
        $image          =   $xPath->query('//meta[@property="og:image"]/@content')->item(0);
        $keywords       =   $xPath->query('//meta[@name="keywords"]/@content')->item(0);

        $title          =   $title ? $title->value : throw new Exception("Error while lookin for title!", 1);
        $description    =   $description ? $description->value : '';
        $image          =   $image ? $image->value : throw new Exception("Error while lookin for title!", 1);
        $keywords       =   $keywords ? $keywords->value : '';

        $articleContent =   $articleDOM->getElementsByTagName('p');

        $paragraph      =   [];
        foreach ($articleContent as $content) {
            if ($p = (string) $content->textContent) {
                if (\preg_match("/Baca Juga: /", $content->textContent)) {
                    continue;
                }

                $paragraph[] = \trim($p);
            }
        }

        $content        =   [];
        foreach ($articleContent as $p) {
            if ($p = (string) $p->textContent) {

                if (\preg_match("/Baca Juga: /", $p)) {
                    continue;
                }

                $content[] = '<p>' . \trim($p) . '</p>';
            }
        }

        // foreach ([0, 1, 2, 3] as $p) {
        //     if ($textContent = $articleContent[$p]) {
        //         if (strlen($textContent->textContent) > 0) {
        //             $content[] = '<p>' . $textContent->textContent . '</p>';
        //         }
        //     }
        // }

        $siteName       =   $xPath->query('//meta[@property="og:site_name"]/@content')->item(0);
        $siteName       =   $siteName ? $siteName->value : '';
        $title          =   \trim(preg_replace('/Halaman\sall|\s-\s(.*)|\s\|(.*)/', '', $title));
        $content        =   str_ireplace($siteName, 'Geratekno', implode("\n", $content));
        $image          =   getThumbnail($image, \true);
        $image          =   Image::create([
            'url'       =>  $image,
            'altText'   =>  $title
        ]);

        $Post           = $keyword->author->posts()->create([
            'title'         =>  $title,
            'description'   =>  \trim($description),
            'keywords'      =>  explode(', ', \trim($keywords)),
            'content'       =>  $content,
            'paragraph'     =>  $paragraph,
            'language'      =>  $keyword->language
        ]);

        if (!$Post) {
            $generated->update([
                'status'  =>  'failed'
            ]);

            throw new Exception("Error while creating a post.", 1);
        }

        $post = $Post->refresh();

        $post->images()->sync($image) ?: throw new Exception("Error while syncing images", 1);
        $post->countries()->sync($keyword->countries) ?: throw new Exception("Error while syncing countries", 1);
        $post->categories()->sync($keyword->categories) ?: throw new Exception("Error while syncing categories", 1);

        $generated->update([
            'status'    =>  'success'
        ]);

        if ((bool) \setting('facebook_active', false)) {
            $categories   = $post->categories()->get(['category_id'])->map(fn ($c) => $c->category_id);
            if (!$categories) {
                return;
            }

            $pages        = Page::where(['active' => true])->whereHas('categories', function ($query) use ($categories) {
                return $query->whereIn('category_id', $categories);
            })->get();

            if (!$pages) {
                return;
            }

            PostFacebook::dispatch($post)->delay(
                Carbon::now()->addMinutes(rand(2, 5))
            );
        }

        if ((bool) \setting('twitter_active')) {
            TwitterPost::dispatch($post)->delay(
                Carbon::now()->addMinutes(rand(2, 5))
            );
        }
    }
}
