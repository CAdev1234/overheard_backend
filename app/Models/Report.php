<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'id',
        'reporter_id',
        'reported_id',
        'reported_post_id',
        'reason',
        'content',
        'isSeen'
    ];
}
