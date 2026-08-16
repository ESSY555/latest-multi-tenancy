<?php

namespace App\Mail;

use App\Models\AdmissionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $isAdminNotification;

    /**
     * Create a new message instance.
     */
    public function __construct(AdmissionApplication $application, $isAdminNotification = false)
    {
        $this->application = $application;
        $this->isAdminNotification = $isAdminNotification;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->isAdminNotification) {
            return new Envelope(
                subject: 'New Admission Application Received - ' . $this->application->branch->name,
            );
        }

        return new Envelope(
            subject: 'Admission Application Received - Thank You!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->isAdminNotification) {
            return new Content(
                view: 'emails.admissions.admin-notification',
            );
        }

        return new Content(
            view: 'emails.admissions.application-received',
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
