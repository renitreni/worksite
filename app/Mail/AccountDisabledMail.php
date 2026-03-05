<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountDisabledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $role;

    public function __construct($name, $role)
    {
        $this->name = $name;
        $this->role = $role;
    }

    public function build()
    {
        return $this->subject('Account Disabled - JobAbroad')
            ->view('emails.account-disabled');
    }
}