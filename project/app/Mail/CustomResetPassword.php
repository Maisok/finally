<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrl;
    public $user;

    public function __construct($resetUrl, $user = null)
    {
        $this->resetUrl = $resetUrl;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Сброс пароля - ' . config('app.name'))
                   ->view('emails.auth.reset-password');
    }
}