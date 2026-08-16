<?php

namespace App\Mail;

use App\Models\AdmissionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $oldStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(AdmissionApplication $application, $oldStatus)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admission Application Status Updated - ' . ucfirst($this->application->status),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admissions.status-updated',
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
