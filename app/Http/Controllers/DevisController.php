<?php

// app/Http/Controllers/DevisController.php
namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\ArticleTableDevis;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    public function index()
    {
        return Devis::with(['client', 'articles'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'montant' => 'required|numeric',
            'statut' => 'required|in:EN_ATTENTE,ACCEPTE,REFUSE',
            'date' => 'required|date',
            'articles' => 'required|array',
            'articles.*.designation' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.quantite' => 'required|integer|min:1',
            'articles.*.prix_unitaire' => 'required|numeric|min:0',
            'articles.*.prix_total' => 'required|numeric|min:0',
        ]);

        // Ajoutez le numéro de devis généré automatiquement
        $validated['num_devis'] = app('documentNumber')->generateDevisNumber();

        $devis = Devis::create($validated);

        foreach ($request->articles as $article) {
            $article['devis_id'] = $devis->id;
            ArticleTableDevis::create($article);
        }

        return $devis->load(['client', 'articles']);
    }

    public function show(Devis $devis)
    {
        return $devis->load(['client', 'articles']);
    }

    public function update(Request $request, Devis $devis)
    {
        $validated = $request->validate([
            'num_devis' => 'string|unique:devis,num_devis,'.$devis->id,
            'client_id' => 'exists:clients,id',
            'montant' => 'numeric',
            'statut' => 'in:EN_ATTENTE,ACCEPTE,REFUSE',
            'date' => 'date',
        ]);

        $devis->update($validated);
        return $devis->load(['client', 'articles']);
    }

    public function destroy(Devis $devis)
    {
        $devis->delete();
        return response()->noContent();
    }

    public function getByClient($clientId)
    {
        return Devis::with(['client', 'articles'])
            ->where('client_id', $clientId)
            ->get();
    }

    public function updateStatut(Request $request, Devis $devis, $statut)
    {
        $devis->update(['statut' => $statut]);
        return $devis;
    }

    public function getArticles(Devis $devis)
    {
        return $devis->articles;
    }
}