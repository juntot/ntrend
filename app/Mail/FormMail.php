<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $formType;
    PUBLIC $emailBody;
    public function __construct($emailBody = null, $subject = '')
    {
        //
        // $this->name = $from;
        // $this->formType = $formType;
        $this->subject = $subject ? $subject : 'Exceltrend Portal';
        $this->emailBody = $emailBody;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('e-Portal@northtrend.com',)
                    ->subject($this->subject)
                    ->view('emails.formNotif');
    }
}
