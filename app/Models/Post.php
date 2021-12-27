<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $casts    =   [
        'keywords'  =>  'array'
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            return $model->slug =    \Str::slug($model->title);
        });
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
}
