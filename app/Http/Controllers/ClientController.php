<?php

// app/Http/Controllers/ClientController.php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{


    public function index()
    {
        return response()->json(Client::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raisonSociale' => 'required|string',
            'adresse' => 'required|string',
            'ville_id' => 'required|numeric',
            'ice' => 'required|string',
            'telephone' => 'required|string',
            'email' => 'required|email',
        ]);
        return Client::create($validated);
    }

    public function show(Client $client)
    {
        return $client->load('ville');
    }

    public function update(Request $request, $id) // Changez le paramètre
    {
        $client = Client::findOrFail($id); // Récupérez explicitement le client
        
        $validated = $request->validate([
            'raisonSociale' => 'string',
            'adresse' => 'string',
            'ville_id' => 'numeric',
            'ice' => 'string',
            'telephone' => 'string',
            'email' => 'email',
        ]);

        $client->update($validated);
        return $client;
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        // Vérifier si le client a des relations liées
        if ($client->bls()->count() > 0 || 
            $client->devis()->count() > 0 || 
            $client->factures()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer ce client car il possède des bons de livraison, devis ou factures associés.'
            ], 422);
        }

        $client->delete();

        return response()->json(['message' => 'Client supprimé avec succès'], 200);
    }

}