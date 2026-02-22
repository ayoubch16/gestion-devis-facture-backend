<?php
// app/Http/Controllers/SharedController.php
namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Devis;
use App\Models\Bl;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDevisEmail;

class SharedController extends Controller
{
    public function getCptFacture()
    {
        return Facture::count();
    }

    public function getCptDevis()
    {
        return Devis::count();
    }

    public function getCptBl()
    {
        return Bl::count();
    }

    public function getCptClient()
    {
        return Client::count();
    }

}
