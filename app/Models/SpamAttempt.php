<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'reason',
        'route',
        'request_method',
        'attempted_at',
    ];

    protected function casts(): array
    {
        return [
            'attempted_at' => 'datetime',
        ];
    }
}