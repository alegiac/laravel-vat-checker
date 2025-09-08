<?php

namespace Alegiac\LaravelVatChecker\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ViesConnectionError extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $vatNumber,
        public string $errorMessage
    ) {}

    public function build(): self
    {
        $subject = (string) config('vat-checker.notifications.mail.subject', 'VAT Checker: VIES connection error');

        $fromAddress = (string) config('vat-checker.notifications.mail.from_address', '');
        $fromName = (string) config('vat-checker.notifications.mail.from_name', 'Laravel VAT Checker');

        $mail = $this->subject($subject)
            ->view('laravel-vat-checker::emails.vies_error')
            ->with([
                'vatNumber' => $this->vatNumber,
                'errorMessage' => $this->errorMessage,
            ]);

        if (!empty($fromAddress)) {
            $mail->from($fromAddress, $fromName);
        }

        return $mail;
    }
}


