<?php

// app/Mail/SendDevisEmail.php
namespace App\Mail;

use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendDevisEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $devis;
    public $pdfPath;

    public function __construct(Devis $devis, string $pdfPath = null)
    {
        $this->devis = $devis;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        $mail = $this->subject('Votre devis n°'.$this->devis->num_devis)
                    ->view('emails.devis')
                    ->with(['devis' => $this->devis]);

        if ($this->pdfPath) {
            $mail->attach(Storage::path($this->pdfPath), [
                'as' => 'devis_'.$this->devis->num_devis.'.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}