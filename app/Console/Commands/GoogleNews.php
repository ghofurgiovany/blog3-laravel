<?php

namespace App\Console\Commands;

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
        return 0;
    }
}
