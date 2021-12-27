<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
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
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
