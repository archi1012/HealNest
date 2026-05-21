<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Message extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'messages';

    protected $fillable = ['sender_id', 'recipient_id', 'body', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}