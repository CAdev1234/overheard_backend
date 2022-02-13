<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $table = 'post_comments';

    protected $fillable = [
        'id',
        'post_id',
        'commenter_id',
        'comment_content',
        // 'voter_id',
        'upvotes',
        'downvotes',
        'comment_datetime'
    ];
}
