<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployerAccountApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employerName;
    public $companyName;

    public function __construct($employerName, $companyName)
    {
        $this->employerName = $employerName;
        $this->companyName = $companyName;
    }

    public function build()
    {
        return $this->subject('Employer Account Approved - JobAbroad')
            ->view('emails.employer-account-approved');
    }
}