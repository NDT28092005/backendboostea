<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscription extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('🎉 Cảm ơn bạn đã đăng ký nhận tin từ Boostea!')
                    ->view('emails.newsletter-subscription')
                    ->with([
                        'email' => $this->email
                    ]);
    }
}

