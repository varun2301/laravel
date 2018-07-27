<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Mail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        //dd($this->data);
        $result = $this->from('friends@hestabit.com','Support')
                    ->subject('Zoho Report')
                    ->view('email.mail_template')
                    ->with([
                        'data' => $this->data
                    ]);

        return $result; 
    }
}
