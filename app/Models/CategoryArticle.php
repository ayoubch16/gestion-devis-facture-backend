<?php

// app/Models/CategoryArticle.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryArticle extends Model
{
    protected $fillable = ['category'];
    
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}