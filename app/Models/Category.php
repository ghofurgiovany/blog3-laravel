<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded  =   [];

    public static function booted()
    {
        static::creating(function ($model) {
            return $model->slug =    \Str::slug($model->name);
        });
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'categoryable', 'categoryables');
    }
}
