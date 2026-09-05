<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp; // Menerima angka OTP dari Controller
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Registrasi Hanania Travel',
        );
    }

    public function content(): Content
    {
        // Mengarahkan ke file tampilan (view) email
        return new Content(
            view: 'emails.otp_register',
        );
    }
}