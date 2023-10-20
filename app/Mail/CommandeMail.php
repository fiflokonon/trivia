<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommandeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $numero_commande;
    public $lien;
    public function __construct($numero_commande, $lien)
    {
        $this->numero_commande = $numero_commande;
        $this->lien = $lien;
    }

    public function build()
    {

        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->view('emails.commande_email')
            ->with([
                'code' => $this->numero_commande,
            ])
            ->subject('Confirmation de votre commande')
            ->attach($this->lien);
    }
}
