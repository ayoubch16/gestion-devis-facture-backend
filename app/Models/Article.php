<?php

// app/Models/Article.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'unite', 'category_article_id', 'name_article',
        'description_article', 'price_article'
    ];
    
    public function category()
    {
        return $this->belongsTo(CategoryArticle::class, 'category_article_id');
    }
}