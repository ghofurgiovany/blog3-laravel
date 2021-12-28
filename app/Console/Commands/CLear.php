<?php

namespace App\Console\Commands;

use App\Models\Google\Generated;
use App\Models\Post;
use Illuminate\Console\Command;

class CLear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Post::truncate();
        Generated::truncate();

        \exec("redis-cli FLUSHALL");

        $this->info("CLeared");

        return 0;
    }
}
