<?php
// app/Http/Controllers/BlController.php
namespace App\Http\Controllers;

use App\Models\Bl;
use App\Models\ArticleTableBl;
use App\Models\Devis;
use Illuminate\Http\Request;

class BlController extends Controller
{
    public function index()
    {
        return Bl::with(['client', 'articles', 'devis'])->get();
    }

    public function createFromDevis($devisId)
    {
        $devis = Devis::with(['client', 'articles'])->findOrFail($devisId);
        
        $bl = Bl::create([
            'num_bl' => 'BL-' . time(),
            'client_id' => $devis->client_id,
            'statut' => 'NON_LIVRE',
            'date' => now()->format('Y-m-d'),
            'devis_id' => $devis->id,
        ]);
        
        foreach ($devis->articles as $article) {
            ArticleTableBl::create([
                'designation' => $article->designation,
                'description' => $article->description,
                'quantite' => $article->quantite,
                'prix_unitaire' => $article->prix_unitaire,
                'prix_total' => $article->prix_total,
                'bl_id' => $bl->id,
            ]);
        }
        
        $devis->update(['bl_existante' => true]);
        
        return $bl->load(['client', 'articles', 'devis']);
    }

    public function update(Request $request, Bl $bl)
    {
        $validated = $request->validate([
            'num_bl' => app('documentNumber')->generateBlNumber(), // Utilisation du service
            'client_id' => 'exists:clients,id',
            'statut' => 'in:LIVRE,NON_LIVRE',
            'date' => 'date',
        ]);

        $bl->update($validated);
        return $bl;
    }

    public function destroy(Bl $bl)
    {
        $bl->delete();
        return response()->noContent();
    }

    public function updateStatut(Request $request, Bl $bl, $statut)
    {
        $bl->update(['statut' => $statut]);
        return $bl;
    }
}