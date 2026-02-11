<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateVerifyEmailCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public int $minutesValid = 10
    ) {}

    public function build()
    {
        return $this->subject('JobAbroad Email Verification Code')
            ->view('emails.candidate-verify-code');
    }
}
