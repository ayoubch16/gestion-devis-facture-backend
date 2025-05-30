<?php

// app/Mail/GenericEmailWithAttachment.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenericEmailWithAttachment extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $messageText;
    public $pdfPath;

    public function __construct($subject, $message, $pdfPath)
    {
        $this->subjectText = $subject;
        $this->messageText = $message;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->view('emails.generic')
                    ->with(['messageText' => $this->messageText])
                    ->attach(Storage::path($this->pdfPath), [
                        'as' => 'document.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
