<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Appointment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'appointments';

    protected $fillable = [
        'user_id',
        'counselor_id',
        'scheduled_at',
        'reason',
        'meeting_type',
        'status',
        'notes',
        'counselor_notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'meeting_type' => 'virtual',
    ];
}
