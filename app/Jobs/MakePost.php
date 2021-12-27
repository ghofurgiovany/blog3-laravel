<?php

namespace App\Jobs;

use App\Models\Google\Keyword;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MakePost implements ShouldQueue
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
    }
}
