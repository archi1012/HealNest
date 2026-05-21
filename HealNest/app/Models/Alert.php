<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Alert extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'alerts';

    protected $fillable = ['user_id', 'triggered_by', 'risk_level', 'status'];

    protected $attributes = ['status' => 'open'];
}
