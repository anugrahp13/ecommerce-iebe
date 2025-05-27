<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'value', 'min_purchase', 'quota', 'start_at', 'end_at',
    ];
}
