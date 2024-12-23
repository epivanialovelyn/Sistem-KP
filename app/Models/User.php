<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['username', 'name', 'nim', 'role', 'password'];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
