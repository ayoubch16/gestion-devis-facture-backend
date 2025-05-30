<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    protected $fillable = ['nom', 'code_postal', 'region'];
    
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}