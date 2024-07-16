<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSettingsModel extends Model
{
    use HasFactory;

    protected $table = 'payment_settings';

    protected $fillable = [
        'key_id',
        'key_secret',
        'mode',
    ];
}
