<?php

namespace App\Mail;

use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InitialColaboratorPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Enterprise $enterprise;
    public User $user;
    public string $password;

    /**
     * Create a new message instance.
     */
    public function __construct(Enterprise $enterprise, User $user, string $password)
    {
        $this->enterprise = $enterprise;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $user_first_name = explode(' ', $this->user->name)[0];
        return new Envelope(
            subject: "Bienvenido a SIGIM, {$user_first_name}! 🎉",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail-templates.initial-colaborator-password',
            with: [
                'enterprise_name' => $this->enterprise->name,
                'user_name' => explode(' ', $this->user->name)[0],
                'password' => $this->password,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
