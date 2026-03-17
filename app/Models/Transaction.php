<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['gateway_id', 'amount', 'status', 'card_last_digits'];
}
