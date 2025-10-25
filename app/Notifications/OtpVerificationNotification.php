<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpVerificationNotification extends Notification
{
    use Queueable;

    protected $otp;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($otp, $type = 'registration')
    {
        $this->otp = $otp;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('K-Glow - Email Verification Code')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for registering with K-Glow.')
            ->line('Your verification code is:')
            ->line('**' . $this->otp . '**')
            ->line('This code will expire in 10 minutes.')
            ->line('If you did not create an account, please ignore this email.')
            ->salutation('Best regards, K-Glow Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
            'type' => $this->type,
        ];
    }
}
