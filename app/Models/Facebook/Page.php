<?php

namespace App\Models\Facebook;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function posts()
    {
        return $this->morphToMany(Post::class, 'postable');
    }
}
