<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RfcNotificationMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $subject;
    public $title;
    public $message;
    public $recipientName;
    public $rfcData;
    public $actionUrl;
    public $actionText;
    
    public function __construct(
        string $title,
        string $message,
        string $recipientName,
        ?array $rfcData = null,
        ?string $actionUrl = null,
        ?string $actionText = null
    ) {
        $this->title = $title;
        $this->message = $message;
        $this->recipientName = $recipientName;
        $this->rfcData = $rfcData;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
        $this->subject = '[SI-RFC] ' . $title;
    }
    
    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.rfc-notification')
            ->with([
                'title' => $this->title,
                'message' => $this->message,
                'recipientName' => $this->recipientName,
                'rfcData' => $this->rfcData,
                'actionUrl' => $this->actionUrl,
                'actionText' => $this->actionText,
            ]);
    }
}
