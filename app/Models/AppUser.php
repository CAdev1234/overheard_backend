<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Client as PassportClient;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AppUser extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'app_users';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'avatar',
        'community_id',
        'registered_date',
        'verified_reported',
        'wallet_balance',
        'isBlocked',
        'email_verified_at'
    ];
}
