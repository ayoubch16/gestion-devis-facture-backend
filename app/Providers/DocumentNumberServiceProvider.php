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
                    $count = Devis::count() + 1;
                    return 'DEV-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
                }

                public function generateFactureNumber()
                {
                    $count = Facture::count() + 1;
                    return 'FAC-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
                }

                public function generateBlNumber()
                {
                    $count = Bl::count() + 1;
                    return 'BL-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
                }
            };
        });
    }
}