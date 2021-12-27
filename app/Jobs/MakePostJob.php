<?php

namespace App\Jobs;

use App\Models\Google\Generated;
use App\Models\Google\Keyword;
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
        if ($this->batch()->cancelled()) {
            return;
        }

        $keyword        = $this->key;
        $articleSource  = Http::get($this->link);

        if ($articleSource->failed()) {
            Generated::where([
                'link'  =>  $this->link
            ])->update([
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
                $paragraph[] = $p;
            }
        }

        $content        =   [];
        foreach ([0, 1, 2, 3] as $p) {
            if ($textContent = $articleContent[$p]) {
                if (strlen($textContent->textContent) > 0) {
                    $content[] = '<p>' . $textContent->textContent . '</p>';
                }
            }
        }

        $siteName       =   $xPath->query('//meta[@property="og:site_name"]/@content')->item(0);
        $siteName       =   $siteName ? $siteName->value : '';
        $title          =   preg_replace('/\s-\s(.*)|Halaman all/', '', $title);
        $content        =   str_ireplace($siteName, 'Geratekno.my.id', implode("\n", $content));
        $image          =   getThumbnail($image);

        $Post           = $keyword->author->posts()->create([
            'title'         =>  $title,
            'description'   =>  $description,
            'keywords'      =>  explode(', ', $keywords),
            'content'       =>  $content,
            'paragraph'     =>  $paragraph,
            'language'      =>  $keyword->language
        ]);

        if (!$Post) {
            Generated::where([
                'link'  =>  $this->link
            ])->update([
                'status'  =>  'failed'
            ]);

            throw new Exception("Error while creating a post.", 1);
        }

        $post = $Post->refresh();
        $post->countries()->sync($keyword->countries);
        $post->categories()->sync($keyword->categories);
    }
}
