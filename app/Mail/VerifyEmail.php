<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userId;
    public $token;

    public function __construct($userId, $token)
    {
        $this->userId = $userId;
        $this->token = $token;
    }

    public function build()
    {
        $verifyUrl = "http://localhost:5173/verify-email?user={$this->userId}&token={$this->token}";

        return $this->subject('Xác nhận email của bạn')
                    ->view('emails.verify-email')
                    ->with([
                        'verifyUrl' => $verifyUrl
                    ]);
    }
}