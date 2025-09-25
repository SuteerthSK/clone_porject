<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','role'];
    protected $hidden = ['password'];

    public function getJWTIdentifier() { return $this->getKey(); }
    public function getJWTCustomClaims() { return []; }

    public function reviews() { return $this->hasMany(Review::class); }
    public function shelves() { return $this->belongsToMany(Book::class, 'user_books')->withPivot('shelf')->withTimestamps(); }
    public function followingAuthors() { return $this->belongsToMany(Author::class, 'user_follows'); }
    public function groups() { return $this->belongsToMany(Group::class, 'group_members'); }
    public function favorites()
{
    return $this->belongsToMany(Book::class, 'favorite_books');
}
public function readingChallenges()
    {
        return $this->hasMany(ReadingChallenge::class);
    }
    
}
