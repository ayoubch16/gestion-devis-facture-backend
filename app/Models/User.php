<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'first_name',
        'last_name', 'is_active', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}