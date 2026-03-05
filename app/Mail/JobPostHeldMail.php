<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class JobPostHeldMail extends Mailable
{
    use Queueable, SerializesModels;

    public $companyName;
    public $jobTitle;
    public $reason;

    public function __construct($companyName, $jobTitle, $reason)
    {
        $this->companyName = $companyName;
        $this->jobTitle = $jobTitle;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Job Post Under Review - JobAbroad')
            ->view('emails.job-post-held');
    }
}