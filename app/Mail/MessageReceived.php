<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Messaje recibido';
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
      $this->data= $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){

      return $this->view('email.SendEmail');
    }
}
