<?php

namespace App\Models\Google;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $table    =   'google_news_keywords';
    protected $guarded  =   [];

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable', 'categoryables');
    }
}
