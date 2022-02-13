<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsSetting extends Model
{
    protected $table = 'ads_settings';

    protected $fillable = [
        'id',
        'display_price',
        'min_number_displays',
        'max_number_displays'
    ];
}
