<?php

// app/Providers/DocumentNumberServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Bl;

class DocumentNumberServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('documentNumber', function () {
            return new class {
                 public function generateDevisNumber()
                {
                    $last = Devis::latest('id')->first();
                    $nextId = $last ? $last->id + 1 : 1;
                    return 'DEV-' . date('y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
                }

                public function generateFactureNumber()
                {
                    $last = Facture::latest('id')->first();
                    $nextId = $last ? $last->id + 1 : 1;
                    return 'FAC-' . date('y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
                }

                public function generateBlNumber()
                {
                    $last = Bl::latest('id')->first();
                    $nextId = $last ? $last->id + 1 : 1;
                    return 'BL-' . date('y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
                }
            };
        });
    }
}