<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','book_id','rating','body'];

    public function user() { return $this->belongsTo(User::class); }
    public function book() { return $this->belongsTo(Book::class); }
    public function likes() { return $this->hasMany(ReviewLike::class); }
    public function comments() { return $this->hasMany(ReviewComment::class); }
}
