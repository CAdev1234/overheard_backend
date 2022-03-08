<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuthVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    private $verficationLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $verficationLink)
    {
        $this->verficationLink = $verficationLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Email Verification')->view('emails.verify')->with([
            'verificationLink' => $this->verficationLink
        ]);
        // return $this->view('view.name');
    }
}
