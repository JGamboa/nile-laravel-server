<?php

namespace JGamboa\NileLaravelServer\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreatedNotification extends Notification
{
    protected string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your account has been created')
            ->greeting("Hello {$notifiable->name},")
            ->line('Your account has been successfully created.')
            ->line("Email: {$notifiable->email}")
            ->line("Temporary Password: **{$this->password}**")
            ->line('Please change your password after logging in.')
            ->action('Login', url('/login'));
    }
}
