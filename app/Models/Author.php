<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    public static function booted()
    {
        static::creating(function ($model) {
            return $model->slug =    \Str::slug($model->title);
        });
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'author_post', 'author_id', 'post_id');
    }

    public function avatar()
    {
        return $this->morphToMany(Image::class, 'imageable');
    }
}
