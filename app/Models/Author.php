<?php

namespace App\Models;

use App\Models\Google\Keyword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $appends  =   ['avatar'];
    protected $guarded  =   [];

    public static function booted()
    {
        static::creating(function ($model) {
            return $model->slug =    \Str::slug($model->name);
        });
    }

    public function googleNewsKeyword()
    {
        return $this->hasMany(Keyword::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    public function getAvatarAttribute()
    {
        return $this->images()->first() ?: [];
    }
}
