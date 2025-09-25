<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    
    // 👇 REMOVED 'photo' from this array
    protected $fillable = ['name', 'biography'];

    public function books() { return $this->hasMany(Book::class); }
    public function followers() { return $this->belongsToMany(User::class, 'user_follows'); }
}