<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class MoodLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'mood_logs';

    protected $fillable = ['user_id', 'mood', 'note', 'tags', 'logged_at'];

    protected $casts = [
        'mood'      => 'integer',
        'tags'      => 'array',
        'logged_at' => 'datetime',
    ];
}
