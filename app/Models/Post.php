<?php

namespace App\Models;

use App\Models\Child\Country;
use Auth;
use DB;
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
    protected $appends  =   ['thumbnail'];

    public static function booted()
    {
        static::creating(function ($model) {
            return $model->slug =    \Str::slug($model->title);
        });

        static::addGlobalScope(function ($builder) {
            return $builder->orderBy('id', 'DESC');
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

    public function getThumbnailAttribute()
    {
        return $this->images()->first() ?: [];
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

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'DESC');
    }
}
