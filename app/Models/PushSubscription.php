<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PushSubscription extends Model
{
    use HasUuids;

    protected $fillable = [
        'endpoint',
        'public_key',
        'auth_token',
    ];
}
