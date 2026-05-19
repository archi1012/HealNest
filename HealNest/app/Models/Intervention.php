<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Intervention extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'interventions';

    protected $fillable = ['user_id', 'type', 'content'];
}
