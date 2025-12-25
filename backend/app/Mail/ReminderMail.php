<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $subjectLine, public string $content)
    {
    }

    public function build(): self
    {
        return $this->subject($this->subjectLine)
            ->view('emails.reminder')
            ->with([
                'content' => $this->content,
            ]);
    }
}
