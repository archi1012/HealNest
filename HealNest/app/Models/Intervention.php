<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Intervention extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'interventions';

    protected $fillable = ['user_id', 'type', 'content'];
}
