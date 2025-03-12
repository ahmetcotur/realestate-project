<?php

namespace App\Mail;

use App\Models\Contact;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PropertyInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * İletişim formu verisi
     */
    public $contact;

    /**
     * Emlak ilanı bilgisi
     */
    public $property;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contact, Property $property)
    {
        $this->contact = $contact;
        $this->property = $property;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Emlak İlanı Hakkında Bilgi Talebi: ' . $this->property->title_tr,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.property-inquiry',
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