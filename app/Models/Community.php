<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $table = 'communities';

    protected $fillable = [
        'id',
        'name',
        'zip_code',
        'participants',
        'radius',
        'ads_price',
        'isApproved',
        'created_at'
    ];
}
