<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct()
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('PUP Supplies Inventory Management System: Password Reset Request')
            ->greeting('Hello!')
            ->line('NOTE: THIS IS SYSTEM GENERATED. DO NOT REPLY.')
            ->line('You are receiving this email because we received a password reset request for your SIMS account.')
            ->line('Upon clicking the link below, you will be asked to input your new password.')
            ->line('Please remember that this link will expire within 5 hours.')
            ->action('Reset Password Link:', url('password/reset/'.$token.'/'))
            ->line('Thank you very much.')
            ->line('')
            ->line('-SIMS Support');
    }
}