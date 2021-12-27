<?php

namespace App\Models\Child;

use App\Models\Google\Keyword;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $guarded  =   [];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'countryable');
    }

    public function keywords()
    {
        return $this->morphedByMany(Keyword::class, 'countryable');
    }
}
