<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Notification sent to the board's inbox when someone uses the public contact form.
 * Reply-To is the visitor, so answering the email goes straight to them.
 */
class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data, public ?string $reference = null)
    {
    }

    public function build()
    {
        return $this->subject('[ITEB website] ' . $this->data['topic_label'] . ' - ' . $this->data['name'])
            ->replyTo($this->data['email'], $this->data['name'])
            ->view('emails.contact-message');
    }
}
