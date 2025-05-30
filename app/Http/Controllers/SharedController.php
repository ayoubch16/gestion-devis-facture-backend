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

    public function sendDevisByEmail(Request $request, $devisId)
    {
        $devis = Devis::with(['client', 'articles'])->findOrFail($devisId);
        
        Mail::to($devis->client->email)
            ->send(new SendDevisEmail($devis));
            
        return response()->json(['message' => 'Devis envoyé par email avec succès']);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'pdf' => 'required|file|mimes:pdf',
        ]);

        $pdf = $request->file('pdf');
        $pdfPath = $pdf->store('temp');

        Mail::to($request->to)
            ->send(new GenericEmailWithAttachment(
                $request->subject,
                $request->message,
                $pdfPath
            ));

        return response()->json(['message' => 'Email envoyé avec succès']);
    }

    public function checkFacture($num)
    {
        return Facture::where('num_facture', $num)->exists();
    }

    public function checkDevis($num)
    {
        return Devis::where('num_devis', $num)->exists();
    }

    public function checkBl($num)
    {
        return Bl::where('num_bl', $num)->exists();
    }
}
