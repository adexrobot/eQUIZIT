<?php

namespace App\Mail;

use App\Models\QuizAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuizSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public QuizAttempt $attempt)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Quiz Submitted - ' . $this->attempt->quiz->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quiz-submitted',
        );
    }
}
