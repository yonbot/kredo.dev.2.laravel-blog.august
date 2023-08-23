<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    # This will not be used
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    # One comment belongs to one user
    # Show the owner of the comment
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
