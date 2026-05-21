<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Resource extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'resources';

    protected $fillable = ['title', 'category', 'icon', 'desc', 'external_url'];
}
