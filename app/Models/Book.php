<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'author_id', 'description', 'image', 'isbn'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorite_books');
}
}
