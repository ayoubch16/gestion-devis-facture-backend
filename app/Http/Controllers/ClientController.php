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
        return response()->json(Client::all());
        // return Client::with('ville')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raison_sociale' => 'required|string',
            'adresse' => 'required|string',
            'ville_id' => 'required|exists:villes,id',
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

    public function update(Request $request, Client $client)
    {
                Log::info('Données reçues:', $request->all());

        $validated = $request->validate([
            'raison_sociale' => 'string',
            'adresse' => 'string',
            'ville_id' => 'exists:villes,id',
            'ice' => 'string',
            'telephone' => 'string',
            'email' => 'email',
        ]);
Log::info('Données validées:', $validated);

        $client->update($validated);
        Log::info('Client après update:', $client->toArray());

        return $client;
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->noContent();
    }

    public function villes()
    {
        return Ville::all();
    }
}