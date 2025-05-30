<?php

// app/Models/Devis.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    protected $fillable = [
        'num_devis', 'client_id', 'montant', 
        'statut', 'date', 'facture_existante', 'bl_existante'
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function articles()
    {
        return $this->hasMany(ArticleTableDevis::class);
    }
    
    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
    
    public function bl()
    {
        return $this->hasOne(Bl::class);
    }
}