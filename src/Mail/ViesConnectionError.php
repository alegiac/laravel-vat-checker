<?php

namespace Alegiac\LaravelVatChecker\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable for VIES connection errors.
 */
class ViesConnectionError extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param string $vatNumber VAT number attempted
     * @param string $errorMessage Error message from SOAP client
     */
    public function __construct(
        public string $vatNumber,
        public string $errorMessage
    ) {}

    /**
     * Build the message.
     *
     * @return self
     */
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


