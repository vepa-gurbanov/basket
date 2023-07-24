<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $blockType;
    public $blockedBy;
    /**
     * Create a new message instance.
     */
    public function __construct($blockType, $blockedBy)
    {
        $this->blockType = $blockType;
        $this->blockedBy = $blockedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'User Deleted',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.admin.user-deleted',
            with: [
                'blockType' => $this->blockType,
                'blockedBy' => $this->blockedBy,
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
