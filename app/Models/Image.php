<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public function authors()
    {
        return $this->morphedByMany(Image::class, 'imageable');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'imageable');
    }
}
