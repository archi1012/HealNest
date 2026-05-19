<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Assessment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'assessments';

    protected $fillable = ['user_id', 'type', 'responses', 'score', 'risk_level', 'taken_at'];

    protected $casts = [
        'responses' => 'array',
        'score'     => 'integer',
        'taken_at'  => 'datetime',
    ];

    public static function calcRisk(string $type, int $score): string
    {
        return match(true) {
            $score <= 4  => 'minimal',
            $score <= 9  => 'mild',
            $score <= 14 => 'moderate',
            default      => 'severe',
        };
    }
}
