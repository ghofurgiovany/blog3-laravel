<?php

namespace App\Models\Google;

use App\Models\Author;
use App\Models\Category;
use App\Models\Child\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $table    =   'google_news_keywords';
    protected $guarded  =   [];
    protected $appends  =   ['country'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable', 'categoryables');
    }

    public function countries()
    {
        return $this->morphToMany(Country::class, 'countryable');
    }

    public function getCountryAttribute()
    {
        return $this->countries()->first();
    }
}
