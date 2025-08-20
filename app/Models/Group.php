<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','is_private','owner_id'];

    public function members() { return $this->belongsToMany(User::class, 'group_members'); }
    public function posts() { return $this->hasMany(DiscussionPost::class); }
}
