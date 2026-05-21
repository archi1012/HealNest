@component('mail::message')
# HealNest Password Reset

Hello {{ $notifiable->name ?? 'there' }},

We received a request to reset your HealNest password.

@component('mail::button', ['url' => $actionUrl, 'color' => 'success'])
Reset Password
@endcomponent

If you did not request this, you can safely ignore this email.

Thanks,
{{ config('app.name') }}
@endcomponent