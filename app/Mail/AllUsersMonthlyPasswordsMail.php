<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AllUsersMonthlyPasswordsMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $accounts;

    public function __construct(array $accounts)
    {
        $this->accounts = $accounts;
    }

    public function build()
    {
        return $this->subject('Rekap Password Bulanan - Jacketing App')
            ->view('emails.all-passwords')
            ->with(['accounts' => $this->accounts]);
    }
}
