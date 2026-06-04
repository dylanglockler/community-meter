<?php

namespace App\Mail;

use App\Models\SurveyResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SurveyResponseReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SurveyResponse $response) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Community Meter Survey Response',
            replyTo: [new \Illuminate\Mail\Mailables\Address(env('MAIL_TO_1'), 'Community Meter')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.survey-response',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
