<?php

namespace App\Console\Commands;

use App;
use App\Jobs\MakePostJob;
use App\Models\Google\Generated;
use App\Models\Google\Keyword;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

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
            if (App::environment(['local'])) {
                $responseRss    =   file_get_contents(base_path('app/Console/Commands/rss.xml'));
            } else {
                $responseRss    =   Http::get('https://news.google.com/rss/search?' . \http_build_query([
                    'hl'    =>  \strtolower($keyword->language) . '-' . \strtoupper($keyword->country->iso2),
                    'gl'    =>  \strtoupper($keyword->country->iso2),
                    'ceid'  =>  \strtoupper($keyword->country->iso2) . ':' . \strtolower($keyword->language),
                    'oc'    =>  200,
                    'q'     =>  $keyword->keyword
                ]));

                if (!$responseRss) {
                    throw new Exception("Failed while getting rss.", $responseRss->getStatusCode());
                }

                $responseRss    = $responseRss->body();
            }

            $rssXml =   \simplexml_load_string($responseRss) or throw new Exception("Cannot parse XML", 1);

            $jobBatch = [];

            foreach ($rssXml->channel->item as $item) {

                $article  = Generated::where([
                    'guid'  =>  (string) $item->guid
                ])->orWhere([
                    'title' =>  (string) $item->title
                ])->orWhere([
                    'link'  =>  (string) $item->link
                ]);

                if (
                    Carbon::parse((string) $item->pubDate) >= Carbon::now()->subHours(
                        \setting('google_news_max_age', 10)
                    )
                ) {
                    continue;
                }

                if ($article->exists()) {
                    continue;
                }

                Generated::create([
                    'guid'  =>  (string) $item->guid,
                    'title' =>  (string) $item->title,
                    'link'  =>  (string) $item->link,
                    'status'   =>   'pending'
                ]);

                $jobBatch[] = new MakePostJob((string) $item->link, $keyword);
            }

            if ($jobBatch && count($jobBatch)) {
                Bus::batch($jobBatch)
                    ->name(
                        'Generate: ' . $keyword->keyword
                    )
                    ->dispatch();
            }
        }
    }
}
