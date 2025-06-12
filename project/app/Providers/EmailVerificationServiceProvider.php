<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class EmailVerificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        VerifyEmail::toMailUsing(function ($notifiable, $verificationUrl) {
            return (new MailMessage)
                ->subject('Подтверждение Email адреса - ' . config('app.name'))
                ->view('emails.auth.verify-email', [
                    'user' => $notifiable,
                    'verificationUrl' => $verificationUrl
                ]);
        });
    }
}