<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $name;
    public $title; // Thêm biến title để đổi tiêu đề linh hoạt

    // Nhận dữ liệu từ Controller truyền qua (Thêm $title)
    public function __construct($code, $name, $title = 'Mã xác nhận')
    {
        $this->code = $code;
        $this->name = $name;
        $this->title = $title;
    }

    // Thiết lập tiêu đề email động
    public function envelope(): Envelope
    {
        return new Envelope(
            // Tiêu đề sẽ lấy từ biến $title truyền vào
            subject: $this->title . ' - Animalia 🐾',
        );
    }

    // Chỉ định file giao diện email
    public function content(): Content
    {
        return new Content(
            // Đảm bảo file này tồn tại trong resources/views/email/forgot_password.blade.php
            view: 'email.forgot_password',
        );
    }
}
