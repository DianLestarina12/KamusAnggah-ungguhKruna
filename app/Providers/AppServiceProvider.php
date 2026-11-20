<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Reset Kata Sandi Admin')
                ->greeting('Halo, Admin!')
                ->line('Kami menerima permintaan reset kata sandi untuk akun Anda.')
                ->action('Reset Kata Sandi', $url)
                ->line('Tautan ini berlaku selama 60 menit.')
                ->line('Jika Anda tidak merasa meminta reset, abaikan email ini.')
                ->salutation('Salam, Tim Kamus Anggah-Ungguh Kruna Basa Bali');
        });
    }
}