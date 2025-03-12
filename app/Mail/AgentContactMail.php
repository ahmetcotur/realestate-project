<?php

namespace App\Mail;

use App\Models\Contact;
use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgentContactMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * İletişim formu verisi
     */
    public $contact;

    /**
     * Danışman bilgisi
     */
    public $agent;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contact, Agent $agent)
    {
        $this->contact = $contact;
        $this->agent = $agent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Danışman İletişim Talebi: ' . $this->agent->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.agent-contact',
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