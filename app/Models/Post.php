<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    # To get the owner of the post
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    # One POST has many COMMENTS
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
