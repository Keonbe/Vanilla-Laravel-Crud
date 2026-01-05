<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Post extends Model
{
    // Migration created table name `post` (singular)
    protected $table = 'post';

    protected $fillable = ['title', 'body', 'user_id']; // Allow mass assignment for these fields

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Each post belongs to a user
    }
}
