<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HealNestResetPassword extends Notification
{
    use Queueable;

    public function __construct(protected string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        return (new MailMessage)
            ->subject('Reset your HealNest password')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We received a request to reset your HealNest password.')
            ->action('Reset Password', $resetUrl)
            ->line('If you did not request this, you can safely ignore this email.')
            ->salutation('Stay safe, HealNest Team');
    }
}