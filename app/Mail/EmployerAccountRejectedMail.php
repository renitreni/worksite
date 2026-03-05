<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployerAccountRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employerName;
    public $companyName;
    public $reason;

    public function __construct($employerName, $companyName, $reason)
    {
        $this->employerName = $employerName;
        $this->companyName = $companyName;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Employer Registration Update - JobAbroad')
            ->view('emails.employer-account-rejected');
    }
}