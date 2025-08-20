<?php

namespace App\Models;

use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title','isbn','description','cover_url','published_at','author_id','publisher'
    ];

    public function author() { return $this->belongsTo(Author::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    
// ...existing code...

public function shelvers()
{
    return $this->belongsToMany(User::class, 'user_books')->withPivot('shelf')->withTimestamps();
}

// ...existing code...
}
