<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SystemTemplateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $htmlBody;
    public ?string $textBody;
    public string $subjectLine;

    public function __construct(string $subjectLine, string $htmlBody, ?string $textBody = null)
    {
        $this->subjectLine = $subjectLine;
        $this->htmlBody = $htmlBody;
        $this->textBody = $textBody;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.raw-html',
            with: [
                'htmlBody' => $this->htmlBody,
            ],
        );
    }

    public function build()
    {
        $mail = $this->subject($this->subjectLine)
            ->view('emails.raw-html', [
                'htmlBody' => $this->htmlBody,
            ]);

        if (!empty($this->textBody)) {
            $mail->text('emails.raw-text', [
                'textBody' => $this->textBody,
            ]);
        }

        return $mail;
    }
}