<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionsModel extends Model
{
    use HasFactory;

    protected $table ='subscriptions';

    protected $fillable = [
        'user_id',
        'amount',
        'duration',
        'expiry_date',
        'status',
        'type'
    ];
}
