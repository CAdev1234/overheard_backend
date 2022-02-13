<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostAttach extends Model
{
    protected $table = 'post_attaches';

    protected $fillable = [
        'id',
        'post_id',
        'filename',
        'url',
        'thumbnail'
    ];
}
