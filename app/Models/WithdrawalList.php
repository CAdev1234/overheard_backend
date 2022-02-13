<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalList extends Model
{
    protected $table = 'withdrawal_list';

    protected $fillable = [
        'id',
        'processing_date',
        'user_id',
        'email',
        'payment_method',
        'amount',
        'status'
    ];

}
