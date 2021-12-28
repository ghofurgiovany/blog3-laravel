<?php

namespace App\Models\Google;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generated extends Model
{
    use HasFactory;

    protected $table    =   'google_news_articles';
    protected $guarded  =   [];
    protected $primaryKey   =   'guid';
}
