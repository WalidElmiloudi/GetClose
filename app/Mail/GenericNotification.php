<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $view;
    public $viewData;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $view = 'emails.notification', array $viewData = [])
    {
        $this->subject = $subject;
        $this->view = $view;
        $this->viewData = $viewData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view($this->view)
                    ->with($this->viewData);
    }
}
