<?php

// app/Mail/SendDevisEmail.php
namespace App\Mail;

use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendDevisEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $devis;

    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
    }

    public function build()
    {
        return $this->subject('Votre devis')
                    ->view('emails.devis')
                    ->with(['devis' => $this->devis]);
    }
}