<?php

namespace App\Models;

use App\Models\Child\Country;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $casts    =   [
        'keywords'  =>  'array',
        'paragraph' =>  'array'
    ];

    protected $guarded  =   [];

    public static function booted()
    {
        static::creating(function ($model) {
            return $model->slug =    \Str::slug($model->title);
        });
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable', 'categoryables');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function countries()
    {
        return $this->morphToMany(Country::class, 'countryable');
    }
}
