<?php

namespace App\Notifications;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpEmailNotification extends Notification
{
    use Queueable;

    protected $client;
    protected $otpCode;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(Client $client, string $otpCode, string $type = 'login')
    {
        $this->client = $client;
        $this->otpCode = $otpCode;
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
        $typeLabel = $this->type === 'activation' ? 'Activation de votre compte' : 'Connexion à votre compte';
        $actionText = $this->type === 'activation' ? 'Activer mon compte' : 'Se connecter';

        return (new MailMessage)
            ->subject("Code de vérification OMPay - {$typeLabel}")
            ->greeting("Bonjour {$this->client->prenom} {$this->client->nom},")
            ->line("Votre code de vérification OMPay pour {$typeLabel} est :")
            ->line('')
            ->line("VOTRE CODE OTP :")
            ->line($this->otpCode)
            ->line('')
            ->line("Ce code expire dans 5 minutes.")
            ->line("Si vous n'avez pas demandé ce code, ignorez cet email.")
            ->salutation("Cordialement,\nL'équipe OMPay");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'client_id' => $this->client->id,
            'otp_code' => $this->otpCode,
            'type' => $this->type,
        ];
    }
}