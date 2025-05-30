<?php

// app/Http/Controllers/FactureController.php
namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\ArticleTableFacture;
use App\Models\Devis;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    public function index()
    {
        return Facture::with(['client', 'articles', 'devis'])->get();
    }

    public function createFromDevis($devisId)
    {
        $devis = Devis::with(['client', 'articles'])->findOrFail($devisId);
        
        $facture = Facture::create([
            'num_facture' => app('documentNumber')->generateFactureNumber(), // Utilisation du service
            'client_id' => $devis->client_id,
            'montant' => $devis->montant,
            'statut' => 'NON_PAYEE',
            'date' => now()->format('Y-m-d'),
            'devis_id' => $devis->id,
        ]);
        
        foreach ($devis->articles as $article) {
            ArticleTableFacture::create([
                'designation' => $article->designation,
                'description' => $article->description,
                'quantite' => $article->quantite,
                'prix_unitaire' => $article->prix_unitaire,
                'prix_total' => $article->prix_total,
                'facture_id' => $facture->id,
            ]);
        }
        
        $devis->update(['facture_existante' => true]);
        
        return $facture->load(['client', 'articles', 'devis']);
    }

    public function update(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'num_facture' => 'string|unique:factures,num_facture,'.$facture->id,
            'client_id' => 'exists:clients,id',
            'montant' => 'numeric',
            'statut' => 'in:NON_PAYEE,PARTIELLEMENT_PAYEE,PAYEE',
            'date' => 'date',
        ]);

        $facture->update($validated);
        return $facture;
    }

    public function destroy(Facture $facture)
    {
        $facture->delete();
        return response()->noContent();
    }

    public function updateStatut(Request $request, Facture $facture, $statut)
    {
        $facture->update(['statut' => $statut]);
        return $facture;
    }
}