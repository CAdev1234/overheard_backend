<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'content',
        'location',
        'lat',
        'lng',
        'upvotes',
        'downvotes',
        'seen_count',
        'comments_count',
        'post_datetime'
    ];
}
