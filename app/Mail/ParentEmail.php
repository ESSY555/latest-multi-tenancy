<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;
    public $parentName;
    public $studentName;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $message, $parentName = null, $studentName = null)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->parentName = $parentName;
        $this->studentName = $studentName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.parent.bulk-email',
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
