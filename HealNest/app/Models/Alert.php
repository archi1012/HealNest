<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Alert extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'alerts';

    protected $fillable = ['user_id', 'triggered_by', 'risk_level', 'status'];

    protected $attributes = ['status' => 'open'];
}
