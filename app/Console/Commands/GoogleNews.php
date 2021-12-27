<?php

namespace App\Console\Commands;

use App\Models\Google\Keyword;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;

class GoogleNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GoogleNews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating content from google news';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $keywords   =   Keyword::all();

        foreach ($keywords as $keyword) {
            $responseRss    =   Http::get('https://news.google.com/rss/search?' . \http_build_query([
                'hl'    =>  \strtolower($keyword->language) . '-' . \strtoupper($keyword->country),
                'gl'    =>  \strtoupper($keyword->country),
                'ceid'  =>  \strtoupper($keyword->country) . ':' . \strtolower($keyword->language),
                'oc'    =>  200,
                'q'     =>  $keyword->keyword
            ]));

            if (!$responseRss) {
                throw new Exception("Failed while getting rss.", $responseRss->getStatusCode());
            }

            $rssXml =   \simplexml_load_string($responseRss->body()) or throw new Exception("Cannot parse XML", 1);
        }
    }
}
