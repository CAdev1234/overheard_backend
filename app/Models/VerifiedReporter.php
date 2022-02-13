<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifiedReporter extends Model
{
    protected $table = 'verified_reporter';

    protected $fillable = [
        'id',
        'user_id',
        'status'
    ];
}
