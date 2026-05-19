<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = ['name', 'email', 'password', 'age', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'age'               => 'integer',
    ];

    public function isAdmin(): bool     { return $this->role === 'admin'; }
    public function isCounselor(): bool { return $this->role === 'counselor'; }
    public function isUser(): bool      { return $this->role === 'user'; }
}
