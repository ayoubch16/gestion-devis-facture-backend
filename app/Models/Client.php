<?php
// app/Models/Client.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'raison_sociale', 'adresse', 'ville_id', 
        'ice', 'telephone', 'email'
    ];
    
    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }
    
    public function devis()
    {
        return $this->hasMany(Devis::class);
    }
    
    public function factures()
    {
        return $this->hasMany(Facture::class);
    }
    
    public function bls()
    {
        return $this->hasMany(Bl::class);
    }
}